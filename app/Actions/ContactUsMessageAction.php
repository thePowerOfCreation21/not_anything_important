<?php

namespace App\Actions;

use App\Http\Resources\ContactUsMessageResource;
use App\Models\ContactUsMessageModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ContactUsMessageAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ContactUsMessageModel::class)
            ->setResource(ContactUsMessageResource::class)
            ->setValidationRules([
                'store' => [
                    'full_name' => ['required', 'string', 'max:150'],
                    'phone_number' => ['required', 'string', 'max:20'],
                    'email' => ['string', 'max:150'],
                    'text' => ['string', 'max:5000'],
                ],
                'getQuery' => [
                    'search' => ['string', 'max:150'],
                    'is_seen' => ['boolean'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                ]
            ])
            ->setCasts([
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent
                        ->where(function ($q) use ($query){
                            $q
                                ->where('full_name', 'LIKE', "%{$query['search']}%")
                                ->orWhere('phone_number', 'LIKE', "%{$query['search']}%")
                                ->orWhere('email', 'LIKE', "%{$query['search']}%");
                        });
                },
                'is_seen' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('is_seen', $query['is_seen']);
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('created_at', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('created_at', '<=', $query['to_date']);
                },
            ])
            ->setOrderBy(['is_seen' => 'ASC', 'id' => 'DESC']);
        parent::__construct();
    }

    /**
     * @param string $id
     * @return object
     * @throws CustomException
     */
    public function getById(string $id): object
    {
        $contactUsMessage = parent::getById($id);

        ContactUsMessageModel::query()
            ->where('id', $contactUsMessage->id)
            ->update([
                'is_seen' => true
            ]);

        return $contactUsMessage;
    }
}

<?php

namespace App\Actions;

use App\Actions\StudentAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\WalletHistoryModel;
use App\Http\Resources\WalletHistoryResource;
use App\Models\StudentModel;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class WalletHistoryAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(WalletHistoryModel::class)
            ->setResource(WalletHistoryResource::class)
            ->setValidationRules([
                'store' => [
                    'student_id' => ['required', 'string', 'max:20'],
                    'amount' => ['required', 'int', 'min:0', 'max:10000000'],
                    'action' => ['required', 'in:increase,decrease']
                ],
                'getQuery' => [
                    'student_id' => ['string', 'max:20']
                ],
                'getByStudent' => [
                    'from_created_at' => ['date_format:Y-m-d'],
                    'to_created_at' => ['date_format:Y-m-d'],
                ]
            ])
            ->setCasts([
                'from_created_at' => ['jalali_to_gregorian:Y-m-d'],
                'to_created_at' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'student_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('student_id', $query['student_id']);
                },
                'from_created_at' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('created_at', '>=', $query['from_created_at']);
                },
                'to_created_at' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('created_at', '<=', $query['from_created_at']);
                },
            ]);

        parent::__construct();
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws CustomException
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $student = (new StudentAction())->getById($data['student_id']);

        $newAmount = $data['action'] == 'increase' ? $student->wallet_amount + $data['amount'] : $student->wallet_amount - $data['amount'];

        if($newAmount < 0)
        {
            throw new CustomException('the value you entered is greater than the current amount in student wallet', 30, Response::HTTP_BAD_REQUEST);
        }

        /*
        if($data['amount'] > 0)
        {
            $newAmount = $data['amount'] + $student->wallet_amount;

            $data['action'] = "increase";
        }
        else
        {
            $data['amount'] = abs($data['amount']);

            $newAmount = $student->wallet_amount - $data['amount'];

            if($newAmount < 0)
            {
                throw new CustomException('the value you entered is greater than the amount in student wallet', 30, Response::HTTP_BAD_REQUEST);
            }

            $data['action'] = "decrease";
        }
        */

        $data['status'] = $data['status'] ?? 'failed';

        $student->update([
            'wallet_amount' => $newAmount
        ]);

        return parent::store($data);
    }

    /**
     * @param callable|null $storing
     * @return mixed
     * @throws CustomException
     */
    public function storeByAdminViaRequest(callable $storing = null): mixed
    {
        return $this->store(
            array_merge($this->getDataFromRequest(), ['status' => 'successful']),
            $storing
        );
    }

    public function getByRequestAndEloquent(): array
    {
        if (isset($this->query['student_id']))
            return array_merge(
                [
                    'student_wallet_amount' => StudentModel::query()
                        ->where('id', $this->query['student_id'])
                        ->first()
                        ->wallet_amount ?? 0
                ],
                parent::getByRequestAndEloquent()
            );
        return parent::getByRequestAndEloquent();
    }
}

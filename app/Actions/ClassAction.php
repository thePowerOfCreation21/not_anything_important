<?php

namespace App\Actions;

use App\Models\ClassModel;
use App\Http\Resources\ClassResource;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassModel::class)
            ->setResource(ClassResource::class)
            ->setValidationRules([
                'storeByAdmin' => [
                    'title' => ['required', 'string', 'min:2', 'max:250'],
                    'level' => ['string', 'min:2', 'max:20'],
                    'educational_year' => ['string', 'max:50']
                ],
                'getQuery' => [
                    'search' => ['string', 'max:150'],
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setQueryToEloquentClosures([
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('title', 'LIKE', "%{$query['search']}%");
                }
            ]);
        parent::__construct();
    }
}

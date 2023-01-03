<?php

namespace App\Actions;


use App\Http\Resources\ClassReportsResource;
use App\Models\ClassReportsModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassReportsAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassReportsModel::class)
            ->setResource(ClassReportsResource::class)
            ->setValidationRules([
                'store' => [
                    'date' => ['required', 'date_format:Y-m-d'],
                    'period' => ['required', 'integer', 'between:1,10'],
                    'class_course_id' => ['required', 'string', 'max:20'],
                    'report' => ['required', 'string', 'max:5000']
                ],
                'update' => [
                    'date' => ['date_format:Y-m-d'],
                    'period' => ['integer', 'between:1,10'],
                    'class_course_id' => ['string', 'max:20'],
                    'report' => ['string', 'max:5000']
                ],
                'getQuery' => [
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                    'class_course_id' => ['integer'],
                    'class_id' => ['integer']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['from_date']);
                },
                'class_course_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('class_course_id', $query['class_course_id']);
                },
                'class_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classCourse', function ($q) use($query){
                        $q->where('class_id', $query['class_id']);
                    });
                }
            ]);
        parent::__construct();
    }
}

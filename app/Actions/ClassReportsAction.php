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
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ]);
        parent::__construct();
    }
}

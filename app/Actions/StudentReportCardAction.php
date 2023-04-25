<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\StudentReportCardModel;
use App\Http\Resources\StudentReportCardResource;

class StudentReportCardAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentReportCardModel::class)
            ->setResource(StudentReportCardResource::class)
            ->setValidationRules([
                'get' => [
                    'search' => ['string', 'max:250'],
                    'month' => ['string', 'max:50'],
                    'educational_year' => ['string', 'max:50'],
                    'class_id' => ['integer'],
                    'student_id' => ['integer']
                ],
                'getByStudent' => [
                    'search' => ['string', 'max:250'],
                    'month' => ['string', 'max:50'],
                    'educational_year' => ['string', 'max:50'],
                    'class_id' => ['integer'],
                ],
            ])
            ->setQueryToEloquentClosures([
                'search' => function ($eloquent, $query)
                {
                    $eloquent->where('title', 'LIKE', "%{$query['search']}%");
                },
                'month' => function ($eloquent, $query)
                {
                    $eloquent->where('month', $query['month']);
                },
                'educational_year' => function ($eloquent, $query)
                {
                    if ($query['educational_year'] != '*')
                        $eloquent->where('educational_year', $query['educational_year']);
                },
                'class_id' => function ($eloquent, $query)
                {
                    $eloquent->where('class_id', $query['class_id']);
                },
                'student_id' => function ($eloquent, $query)
                {
                    $eloquent->where('student_id', $query['student_id']);
                },
            ]);
        parent::__construct();
    }
}

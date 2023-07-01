<?php

namespace App\Actions;

use App\Http\Resources\ClassMessageStudentResource;
use App\Models\ClassMessageStudentModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassMessageStudentAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassMessageStudentModel::class)
            ->setResource(ClassMessageStudentResource::class)
            ->setValidationRules([
                'getByStudent' => [
                    'is_seen' => ['boolean'],
                    'class_id' => ['integer'],
                    'from_created_at' => ['string'],
                    'to_created_at' => ['string'],
                ]
            ])
            ->setCasts([
                'from_created_at' => ['jalali_to_gregorian:Y-m-d'],
                'to_created_at' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'student_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->where('student_id', $query['student_id']);
                },
                'is_seen' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->where('is_seen', $query['is_seen']);
                },
                'class_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('classMessage', function ($q) use ($query) {
                        $q->where('class_messages.class_id', $query['class_id']);
                    });
                },
                'from_created_at' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('classMessage', function ($q) use ($query) {
                        $q->whereDate('date', '>=', $query['from_created_at']);
                    });
                },
                'to_created_at' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('classMessage', function ($q) use ($query) {
                        $q->whereDate('date', '<=', $query['to_created_at']);
                    });
                },
            ]);

        parent::__construct();
    }
}

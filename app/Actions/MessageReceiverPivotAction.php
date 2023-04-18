<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\MessageReceiverPivotModel;
use App\Http\Resources\MessageReceiverPivotResource;

class MessageReceiverPivotAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(MessageReceiverPivotModel::class)
            ->setResource(MessageReceiverPivotResource::class)
            ->setValidationRules([
                'get' => [
                    'is_seen' => ['boolean'],
                    'type' => ['string', 'max:50']
                ]
            ])
            ->setQueryToEloquentClosures([
                'student_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent
                        ->where('receiver_type', 'App\\Models\\StudentModel')
                        ->where('receiver_id', $query['student_id']);
                },
                'teacher_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent
                        ->where('receiver_type', 'App\\Models\\TeacherModel')
                        ->where('receiver_id', $query['teacher_id']);
                },
                'is_seen' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('is_seen', $query['is_seen']);
                },
                'type' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('message', function($q) use($query){
                        $q->where('type', $query['type']);
                    });
                }
            ]);
        parent::__construct();
    }
}

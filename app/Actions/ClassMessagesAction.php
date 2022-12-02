<?php

namespace App\Actions;


use App\Http\Resources\ClassMessagesResource;
use App\Models\ClassMessageModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassMessagesAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassMessageModel::class)
            ->setResource(ClassMessagesResource::class)
            ->setValidationRules([
                'store' => [
                    'class_id' => ['required', 'string', 'max:20'],
                    'student_id' => ['nullable', 'string', 'max:20'],
                    'text' => ['required', 'string', 'min:2', 'max:20']
                ]
            ]);
        parent::__construct();
    }
}

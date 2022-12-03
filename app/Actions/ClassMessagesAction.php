<?php

namespace App\Actions;


use App\Http\Resources\ClassMessagesResource;
use App\Models\ClassMessageModel;
use App\Models\ClassMessageStudentModel;
use App\Models\ClassModel;
use Genocide\Radiocrud\Exceptions\CustomException;
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
                    'text' => ['required', 'string', 'min:2', 'max:20']
                ]
            ]);

        parent::__construct();
    }

    public function store(array $data, callable $storing = null): mixed
    {
        $class = ClassModel::query()
            ->with('students')
            ->where('id', $data['class_id'])
            ->first();

        if (empty($class))
        {
            throw new CustomException('class_id is wrong', 2102, 400);
        }

        if (empty($class->students))
        {
            throw new CustomException('This class has no students', 2102, 400);
        }

        $classMessage = parent::store($data, $storing);

        $classMessageStudents = [];

        foreach ($class->students AS $student)
        {
            $classMessageStudents[] = [
                'class_message_id' => $classMessage->id,
                'student_id' => $student->id,
            ];
        }

        ClassMessageStudentModel::insert($classMessageStudents);

        return $classMessage;
    }
}

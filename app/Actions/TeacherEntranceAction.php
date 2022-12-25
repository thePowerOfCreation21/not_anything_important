<?php

namespace App\Actions;

use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\TeacherEntranceModel;
use App\Http\Resources\TeacherEntranceResource;

class TeacherEntranceAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(TeacherEntranceModel::class)
            ->setResource(TeacherEntranceResource::class)
            ->setValidationRules([
                'store' => [
                    'week_day' => ['required', 'integer', 'between:1,7'],
                    'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
                    'entrance' => ['required', 'date_format:H:i'],
                    'exit' => ['date_format:H:i'],
                ]
            ]);

        parent::__construct();
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws \Throwable
     */
    public function store(array $data, callable $storing = null): mixed
    {
        throw_if(
            TeacherEntranceModel::query()->where('teacher_id', $data['teacher_id'])->where('week_day', $data['week_day'])->exists(),
            CustomException::class,
            'this teacher already has an entrance on this week day',
            4335
        );

        return parent::store($data, $storing);
    }
}

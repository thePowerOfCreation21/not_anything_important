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
                    'week_day' => ['required', 'integer', 'between:0,6'],
                    'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
                    'entrance' => ['required', 'date_format:H:i'],
                    'exit' => ['date_format:H:i'],
                ],
                'update' => [
                    'week_day' => ['integer', 'between:0,6'],
                    'teacher_id' => ['integer', 'exists:teachers,id'],
                    'entrance' => ['date_format:H:i'],
                    'exit' => ['nullable', 'date_format:H:i'],
                ],
                'getQuery' => [
                    'teacher_id' => ['integer'],
                    'week_day' => ['integer', 'between:0,6']
                ],
                'getByTeacher' => [
                    'week_day' => ['integer', 'between:0,6']
                ]
            ])
            ->setQueryToEloquentClosures([
                'teacher_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('teacher_id', $query['teacher_id']);
                },
                'week_day' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('week_day', $query['week_day']);
                }
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

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     * @throws \Throwable
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        foreach ($this->getEloquent()->get() AS $teacherEntrance)
        {
            throw_if(
                TeacherEntranceModel::query()
                    ->where('teacher_id', $updateData['teacher_id'])
                    ->where('week_day', $updateData['week_day'])
                    ->where('id', '!=', $teacherEntrance->id)
                    ->exists(),
                CustomException::class,
                'this teacher already has an entrance on this week day',
                4335
            );
        }

        return parent::update($updateData, $updating);
    }
}

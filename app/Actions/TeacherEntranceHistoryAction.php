<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Models\TeacherEntranceModel;
use Genocide\Radiocrud\Helpers;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\TeacherEntranceHistoryModel;
use App\Http\Resources\TeacherEntranceHistoryResource;

class TeacherEntranceHistoryAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(TeacherEntranceHistoryModel::class)
            ->setResource(TeacherEntranceHistoryResource::class)
            ->setValidationRules([
                'store' => [
                    'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
                    'date' => ['date_format:Y-m-d'],
                    'entrance' => ['required', 'date_format:H:i'],
                    'exit' => ['date_format:H:i'],
                ],
                'update' => [
                    'exit' => ['date_format:H:i'],
                ],
                'getQuery' => [
                    'teacher_id' => ['integer'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ]
            ])
            ->setCasts([
                'week_day' => ['integer', 'between:1,7'],
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'teacher_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('teacher_id', $query['teacher_id']);
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['from_date']);
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
     * @throws \Exception
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $data['date'] = $data['date'] ?? date('Y-m-d');
        $data['week_day'] = PardisanHelper::getWeekDayByGregorianDate($data['date']);

        $teacherEntrance = TeacherEntranceModel::query()->where('week_day', $data['week_day'])->firstOrFail();

        if (strtotime($data['entrance']) > strtotime($teacherEntrance->entrance))
        {
            $data['late_string'] = Helpers::persianTimeElapsedString($data['entrance'], $teacherEntrance->entrance, full: true);
        }

        // TODO: send sms

        return parent::store($data, $storing);
    }
}

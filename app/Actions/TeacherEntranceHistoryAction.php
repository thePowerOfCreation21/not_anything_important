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
                    'date' => ['required', 'date_format:Y-m-d'],
                    'entrance' => ['required', 'date_format:H:i'],
                    'exit' => ['date_format:H:i'],
                ],
                'update' => [
                    'exit' => ['date_format:H:i'],
                ],
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
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

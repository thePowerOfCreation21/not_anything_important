<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Models\TeacherEntranceModel;
use App\Models\TeacherModel;
use Genocide\Radiocrud\Helpers;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\TeacherEntranceHistoryModel;
use App\Http\Resources\TeacherEntranceHistoryResource;
use Genocide\Radiocrud\Services\SendSMSService;

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
                    'date' => ['string'],
                    // 'week_day' => ['required', 'between:1,7'],
                    // 'entrance' => ['required', 'boolean'],
                    'exit' => ['boolean'],
                ],
                'update' => [
                    'exit' => ['boolean'],
                ],
                'getQuery' => [
                    'week_day' => ['integer', 'between:0,6'],
                    'teacher_id' => ['integer'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                ],
                'getByTeacher' => [
                    'week_day' => ['integer', 'between:0,6'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'teacher_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->where('teacher_id', $query['teacher_id']);
                },
                'from_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['to_date']);
                },
                'week_day' => function (&$eloquent, $query) {
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
        $data['jalali_date'] = Helpers::getCustomDateCast($data['date'])->jdate;
        $data['week_day'] = PardisanHelper::getWeekDayByGregorianDate($data['date']);
        // $data['week_day'] = PardisanHelper::getWeekDayByGregorianDate($data['date']);

        $data['entrance'] = date('h:i');
        if (isset($data['exit']) && $data['exit']) $data['exit'] = date('h:i');

        $teacherEntrance = TeacherEntranceModel::query()
            ->with('teacher')
            ->where('week_day', $data['week_day'])
            ->where('teacher_id', $data['teacher_id'])
            ->firstOrFail();

//        dd([
//            'entrance' => $data['entrance'],
//            'teacher_entrance' => $teacherEntrance->entrance,
//            'entrance_time' => strtotime($data['entrance']),
//            'teacher_entrance_time' => strtotime($teacherEntrance->entrance),
//            'week_day' => $data['week_day'],
//            'teacher_entrance_array' => $teacherEntrance->toArray()
//        ]);

        if (strtotime($data['entrance']) > strtotime($teacherEntrance->entrance)) {
            $data['late_string'] = Helpers::persianTimeElapsedString($data['entrance'], $teacherEntrance->entrance, full: true);

            if (!empty($teacherEntrance->teacher?->phone_number)) (new SendSMSService())->sendOTP($teacherEntrance->teacher->phone_number, 'teacherLate', $data['jalali_date'], $data['late_string']);
        }

        $updateTeacherData = [
            'last_entrance_date' => date('Y-m-d')
        ];

        if (isset($data['exit'])) $updateTeacherData['last_exit_date'] = date('Y-m-d');

        TeacherModel::query()
            ->where('id', $data['teacher_id'])
            ->update($updateTeacherData);

        return parent::store($data, $storing);
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        if (isset($updateData['entrance']) && $updateData['entrance']) $updateData['entrance'] = date('h:i');
        if (isset($updateData['exit']) && $updateData['exit']) $updateData['exit'] = date('h:i');

        if (isset($updateData['exit']))
            foreach ($this->eloquent->get() as $teacherEntranceHistory)
                TeacherModel::query()
                    ->where('id', $teacherEntranceHistory->teacher_id)
                    ->update([
                        'last_exit_date' => date('Y-m-d')
                    ]);

        return parent::update($updateData, $updating); // TODO: Change the autogenerated stub
    }
}

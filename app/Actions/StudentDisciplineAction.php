<?php

namespace App\Actions;

use App\Http\Resources\StudentDisciplineResource;
use App\Models\StudentModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Helpers\PardisanHelper;
use App\Models\StudentDisciplineModel;
use Genocide\Radiocrud\Services\SendSMSService;

class StudentDisciplineAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentDisciplineModel::class)
            ->setResource(StudentDisciplineResource::class)
            ->setValidationRules([
                'store' => [
                    'student_id' => ['required', 'string', 'max:20'],
                    'title' => ['required', 'string', 'max:250'],
                    'description' => ['required', 'string', 'max:500'],
                    'date' => ['required', 'string']
                ],
                'update' => [
                    'title' => ['string', 'max:250'],
                    'description' => ['string', 'max:500'],
                    'date' => ['string']
                ],
                'getQuery' => [
                    'student_id' => ['string', 'max:20'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                    'educational_year' => ['string', 'max:50']
                ],
                'getByStudent' => [
                    'is_seen' => ['bool'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'student_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->where('student_id', $query['student_id']);
                },
                'is_seen' => function (&$eloquent, $query) {
                    $query['is_seen'] !== '*' && $eloquent = $eloquent->where('is_seen', $query['is_seen']);
                },
                'from_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['to_date']);
                },
                'educational_year' => function (&$eloquent, $query) {
                    if ($query['educational_year']  != '*') {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                }
            ]);

        parent::__construct();
    }

    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($data['date']);
        $student = StudentModel::query()->findOrFail($data['student_id']);
        (new SendSMSService())->sendOTP([$student->father_mobile_number, $student->mother_mobile_number], 'studentDisciplineAdded', $student->full_name);

        return parent::store($data, $storing);
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        if (isset($updateData['date'])) {
            $updateData['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($updateData['date']);
        }

        return parent::update($updateData, $updating);
    }

    protected function getFirstByEloquent($eloquent = null): object
    {

        $entity = parent::getFirstByEloquent();

        $this->model::query()->where('id', $entity->id)->update(['is_seen' => true]);

        return $entity;
    }
}

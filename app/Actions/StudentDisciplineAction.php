<?php

namespace App\Actions;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Helpers\PardisanHelper;
use App\Models\StudentDisciplineModel;

class StudentDisciplineAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentDisciplineModel::class)
            ->setValidationRules([
                'store' => [
                    'student_id' => ['required', 'string', 'max:20'],
                    'title' => ['required', 'string', 'max:250'],
                    'description' => ['required', 'string', 'max:500'],
                    'date' => ['required', 'date_format:Y-m-d']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ]);

        parent::__construct();
    }

    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($data['date']);

        return parent::store($data, $storing);
    }
}

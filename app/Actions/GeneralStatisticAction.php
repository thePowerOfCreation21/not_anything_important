<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\GeneralStatisticResource;
use App\Models\GeneralStatisticModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use JetBrains\PhpStorm\ArrayShape;

class GeneralStatisticAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setValidationRules([
                'getQuery' => [
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ]
            ])
            ->setCasts([
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ]);

        parent::__construct();
    }

    /**
     * @param array $query
     * @return int[]
     */
    #[ArrayShape(['paid' => "int|mixed", 'not_paid' => "int|mixed", 'total' => "int|mixed"])]
    public function getStudentFinancialStatistics (array $query): array
    {
        $stats = [
            'paid' => 0,
            'not_paid' => 0,
            'total' => 0
        ];

        foreach (
            (new StudentFinancialAction())
                ->setQuery($query)
                ->makeEloquent()
                ->getByEloquent()
            AS $studentFinancial
        )
        {
            if ($studentFinancial->paid)
            {
                $stats['paid'] += $studentFinancial->amount;
            }
            else
            {
                $stats['not_paid'] += $studentFinancial->amount;
            }
            $stats['total'] += $studentFinancial->amount;
        }

        return $stats;
    }

    /**
     * @param array $query
     * @return int[]
     */
    #[ArrayShape(['total' => "int|mixed"])]
    public function getTeacherFinancialStatistics (array $query): array
    {
        $stats = [
            'total' => 0
        ];

        foreach (
            (new TeacherFinancialAction())
                ->setQuery($query)
                ->makeEloquent()
                ->getByEloquent()
            AS $teacherFinancial
        )
        {
            $stats['total'] += $teacherFinancial->amount;
        }

        return $stats;
    }

    /**
     * @param array $query
     * @return int[]
     */
    #[ArrayShape(['total' => "int|mixed"])]
    public function getFinancialStatistics (array $query): array
    {
        $stats = [
            'total' => 0
        ];

        foreach (
            (new FinancialAction())
                ->setQuery($query)
                ->makeEloquent()
                ->getByEloquent()
            AS $financial
        )
        {
            $stats['total'] += $financial->amount;
        }

        return $stats;
    }

    /**
     * @param array $query
     * @return array
     */
    #[ArrayShape(['student_financial' => "int[]", 'teacher_financial' => "int[]", 'financial' => "int[]"])]
    public function get (array $query): array
    {
        $query['educational_year'] = $query['educational_year'] ?? PardisanHelper::getCurrentEducationalYear();

        return [
            'student_financial' => $this->getStudentFinancialStatistics($query),
            'teacher_financial' => $this->getTeacherFinancialStatistics($query),
            'financial' => $this->getFinancialStatistics($query)
        ];
    }

    /**
     * @return array
     * @throws CustomException
     */
    #[ArrayShape(['student_financial' => "int[]", 'teacher_financial' => "int[]", 'financial' => "int[]"])]
    public function getByRequest (): array
    {
        return $this->get($this->getDataFromRequest());
    }
}

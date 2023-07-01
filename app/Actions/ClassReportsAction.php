<?php

namespace App\Actions;


use App\Http\Resources\ClassReportsResource;
use App\Models\ClassReportsModel;
use Genocide\Radiocrud\Helpers;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassReportsAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassReportsModel::class)
            ->setResource(ClassReportsResource::class)
            ->setValidationRules([
                'store' => [
                    'date' => ['required', 'string'],
                    'period' => ['required', 'integer', 'between:1,10'],
                    'class_course_id' => ['required', 'string', 'max:20'],
                    'report' => ['required', 'string', 'max:5000']
                ],
                'update' => [
                    'date' => ['string'],
                    'period' => ['integer', 'between:1,10'],
                    'class_course_id' => ['string', 'max:20'],
                    'report' => ['string', 'max:5000']
                ],
                'getQuery' => [
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                    'date_timestamp' => ['integer'],
                    'class_course_id' => ['integer'],
                    'class_id' => ['integer'],
                    'course_id' => ['integer']
                ],
                'getByStudent' => [
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                    'date_timestamp' => ['integer'],
                    'class_course_id' => ['integer'],
                    'class_id' => ['integer'],
                    'course_id' => ['integer']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'from_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['from_date']);
                },
                'date_timestamp' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->wheredate('date', date('Y-m-d', $query['date_timestamp']));
                },
                'class_course_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->where('class_course_id', $query['class_course_id']);
                },
                'course_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('classCourse', function ($q) use ($query) {
                        $q->where('course_id', $query['course_id']);
                    });
                },
                'class_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('classCourse', function ($q) use ($query) {
                        $q->where('class_id', $query['class_id']);
                    });
                },
                'student_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->forStudent($query['student_id']);
                },
                'teacher_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->forTeacher($query['teacher_id']);
                }
            ]);
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getInCustomFormat(): array
    {
        $hashMapDateToClassReport = [];
        foreach ($this->eloquent->get() as $classReport) {
            $jdate = explode(' ', Helpers::getCustomDateCast($classReport->date)['jdate'])[0];
            $hashMapDateToClassReport[$jdate]['date'] = $jdate;
            $hashMapDateToClassReport[$jdate]['class_reports'][] = $classReport;
        }

        return array_values($hashMapDateToClassReport);
    }

    /**
     * @return $this
     */
    public function groupByDate(): static
    {
        $this->eloquent = $this
            ->eloquent
            ->select(
                DB::raw("DATE_FORMAT(`date`, '%Y-%m-%d 00:00:00') AS `date`"),
                DB::raw("count(`id`) as `count`")
            )
            ->groupBy(DB::raw("DATE_FORMAT(`date`, '%Y-%m-%d 00:00:00')"));

        $sql = $this->eloquent->toSql();

        $bindings = array_map(
            fn ($parameter) => is_string($parameter) ? "'$parameter'" : $parameter,
            $this->eloquent->getBindings()
        );

        $sql = Str::replaceArray('?', $bindings, $sql);

        $this->eloquent = DB::table(DB::raw("($sql) AS sub"));

        return $this;
    }
}

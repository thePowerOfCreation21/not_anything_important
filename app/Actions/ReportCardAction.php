<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Models\ClassModel;
use App\Models\ReportCardExamModel;
use App\Models\ReportCardExamScoreModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ReportCardModel;
use App\Http\Resources\ReportCardResource;

class ReportCardAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'max:150'],
                    'month' => ['required', 'string', 'max:100'],
                    'educational_year' => ['string', 'max:100'],
                    'class_id' => ['required', 'integer']
                ],
            ])
            ->setModel(ReportCardModel::class)
            ->setResource(ReportCardResource::class);
        parent::__construct();
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws \Throwable
     */
    protected function store(array $data, callable $storing = null): mixed
    {
        throw_if(
            ReportCardModel::query()->where('class_id', $data['class_id'])->where('was_issued', false)->exists(),
            CustomException::class, 'this class already has a not issued report card', '416732', '400'
        );

        $class = ClassModel::query()
            ->whereHas('courses.course')
            ->whereHas('students')
            ->with([
                'courses.course',
                'students'
            ])
            ->firstOrFail();

        $data['educational_year'] = $data['educational_year'] ?? PardisanHelper::getCurrentEducationalYear();

        $reportCard = parent::store($data, $storing);

        $class->courses->map(function($classCourse) use ($reportCard, $class){
            if (!empty($classCourse->course))
            {
                $reportCardExam = ReportCardExamModel::query()->create([
                    'report_card_id' => $reportCard->id,
                    'course_id' => $classCourse->course_id
                ]);

                $reportCardExamScores = [];
                $class->students->map(function($student) use($reportCardExam, $reportCardExamScores){
                    $reportCardExamScores[] = [
                        'report_card_exam_id' => $reportCardExam->id,
                        'student_id' => $student->id
                    ];
                });
                ReportCardExamScoreModel::query()->insert($reportCardExamScores);
            }
        });

        return $reportCard;
    }
}

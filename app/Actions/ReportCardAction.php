<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Models\ClassModel;
use App\Models\ReportCardExamModel;
use App\Models\ReportCardExamScoreModel;
use App\Models\StudentReportCardModel;
use App\Models\StudentReportCardScoreModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ReportCardModel;
use App\Http\Resources\ReportCardResource;

class ReportCardAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ReportCardModel::class)
            ->setResource(ReportCardResource::class)
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'max:150'],
                    'month' => ['required', 'string', 'max:100'],
                    'educational_year' => ['string', 'max:100'],
                    'class_id' => ['required', 'integer']
                ],
                'get' => [
                    'class_id' => ['integer'],
                    'month' => ['string', 'max:100'],
                    'educational_year' => ['string', 'max:50'],
                    'was_issued' => ['boolean'],
                    'level' => ['string', '150'],
                    'search' => ['string', 'max:150'],
                ],
                'issueReportCards' => [
                    'class_id' => ['integer'],
                    'month' => ['string', 'max:100'],
                    'educational_year' => ['string', 'max:50'],
                    'level' => ['string', '150'],
                    'search' => ['string', 'max:150'],
                ],
            ])
            ->setQueryToEloquentClosures([
                'class_id' => function(&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('class_id', $query['class_id']);
                },
                'month' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('month', $query['month']);
                },
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year'] != '*')
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                },
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('title', 'LIKE', "%{$query['search']}%");
                },
                'level' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classModel', function($q) use($query){
                        $q->where('level', $query['level']);
                    });
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
                $class->students->map(function($student) use($reportCardExam, &$reportCardExamScores){
                    $reportCardExamScores[] = [
                        'report_card_exam_id' => $reportCardExam->id,
                        'student_id' => $student->id,
                        'is_present' => true,
                        'score' => 0
                    ];
                });
                ReportCardExamScoreModel::query()->insert($reportCardExamScores);
            }
        });

        return $reportCard;
    }

    public function issueReportCardsByEloquent ()
    {
        $reportCards = $this->eloquent->get();

        $hashMapStudentIdToStudentReportCard = [];
        $hashMapClassIdCourseIdToClassCourseScoreStats = [];

        foreach ($reportCards AS $reportCard)
        {
            $level = $reportCard->classModel->level ?? 'unknown';

            foreach ($reportCard->reportCardExams ?? [] AS $reportCardExam)
            {
                foreach ($reportCardExam->reportCardExamScores AS $reportCardExamScore)
                {

                    if (! isset($hashMapStudentIdToStudentReportCard[$reportCardExamScore->student_id]))
                    {
                        $hashMapStudentIdToStudentReportCard[$reportCardExamScore->student_id] = [
                            'title' => $reportCard->title,
                            'month' => $reportCard->month,
                            'educational_year' => $reportCard->educational_year,
                            'class_id' => $reportCard->class_id,
                            'student_id' => $reportCardExamScore->student_id,
                            'average_score' => 0,
                            'total_score' => 0,
                            'total_ratio' => 0,
                            'scores' => [],
                            '__level' => $level
                        ];
                    }

                    if (! isset($hashMapClassIdCourseIdToClassCourseScoreStats["{$reportCard->classModel->id}.{$reportCardExam->course_id}"]))
                    {
                        $hashMapClassIdCourseIdToClassCourseScoreStats["{$reportCard->classModel->id}.{$reportCardExam->course_id}"] = [
                            'highest_score_in_class' => 0,
                            'average_score' => 0,
                            'total_score' => 0,
                            'course_count' => 0
                        ];
                    }

                    if ($reportCardExamScore->is_present)
                    {
                        $studentReportCard = &$hashMapStudentIdToStudentReportCard[$reportCardExamScore->student_id];
                        $classCourseScoreStats = &$hashMapClassIdCourseIdToClassCourseScoreStats["{$reportCard->classModel->id}.{$reportCardExam->course_id}"];

                        $studentReportCard['total_score'] += $reportCardExamScore->score * $reportCardExam->course->ratio;
                        $studentReportCard['total_ratio'] += $reportCardExam->course->ratio;
                        $studentReportCard['average_score'] = $studentReportCard['total_score'] / $studentReportCard['total_ratio'];
                        $studentReportCard['scores']["{$reportCardExamScore->student_id}.{$reportCardExam->course_id}"] = [
                            'score' => $reportCardExamScore->score,
                            'course_id' => $reportCardExam->course_id,
                            '__level' => $level,
                            '__class_id' => $studentReportCard->class_id,
                            '__student_id' => $reportCardExamScore->student_id
                        ];

                        $classCourseScoreStats['highest_score_in_class'] = max($classCourseScoreStats['highest_score_in_class'], $reportCardExamScore->score);
                        $classCourseScoreStats['total_score'] += $reportCardExamScore->score;
                        $classCourseScoreStats['course_count']++;
                        $classCourseScoreStats['average_score'] = $classCourseScoreStats['total_score'] / $classCourseScoreStats['average_score'];
                    }
                }
            }
        }

        array_multisort($hashMapStudentIdToStudentReportCard, SORT_DESC, array_column($hashMapStudentIdToStudentReportCard, 'average_score'));

        $hashMapLevelToReportCardScoreStats = [];
        $hashMapClassIdToReportCardScoreStats = [];

        foreach ($hashMapStudentIdToStudentReportCard AS &$studentReportCard)
        {
            if (!isset($hashMapLevelToReportCardScoreStats[$studentReportCard['__level']]))
            {
                $hashMapLevelToReportCardScoreStats[$studentReportCard['__level']] = [
                    'last_highest_score' => $studentReportCard['average_score'],
                    'rank' => 1
                ];
            }

            if ($hashMapLevelToReportCardScoreStats[$studentReportCard['__level']]['last_highest_score'] != $studentReportCard['average_score'])
            {
                $hashMapLevelToReportCardScoreStats[$studentReportCard['__level']] = $studentReportCard['average_score'];
                $hashMapLevelToReportCardScoreStats[$studentReportCard['__level']]['rank']++;
            }

            if (!isset($hashMapClassIdToReportCardScoreStats[$studentReportCard['class_id']]))
            {
                $hashMapClassIdToReportCardScoreStats[$studentReportCard['class_id']] = [
                    'last_highest_score' => $studentReportCard['average_score'],
                    'rank' => 1
                ];
            }

            if ($hashMapClassIdToReportCardScoreStats[$studentReportCard['class_id']]['last_highest_score'] != $studentReportCard['average_score'])
            {
                $hashMapClassIdToReportCardScoreStats[$studentReportCard['class_id']] = $studentReportCard['average_score'];
                $hashMapClassIdToReportCardScoreStats[$studentReportCard['class_id']]['rank']++;
            }

            $studentReportCard['rank_in_class'] = $hashMapClassIdToReportCardScoreStats[$studentReportCard['class_id']]['rank'];
            $studentReportCard['rank_in_level'] = $hashMapLevelToReportCardScoreStats[$studentReportCard['__level']]['rank'];

            foreach ($studentReportCard['scores'] AS &$studentReportCardScore)
            {
                if ($studentReportCardScore['score'] < ($hashMapClassIdCourseIdToClassCourseScoreStats["{$studentReportCard['class_id']}.{$studentReportCardScore['course_id']}"] - 2))
                {
                    $studentReportCardScore['has_star'] = true;
                }
            }

            $allScores = isset($allScores) ? array_merge($allScores, $studentReportCard['scores']) : $studentReportCard['scores'];
        }

        array_multisort($allScores, SORT_DESC, array_column($allScores, 'score'));

        $hashMapLevelCourseIdToCourseScoreStats = [];
        $hashMapClassIdCourseIdToCourseScoreStats = [];
        foreach ($allScores AS $score)
        {
            if (!isset($hashMapLevelCourseIdToCourseScoreStats["{$score['__level']}.{$score['course_id']}"]))
            {
                $hashMapLevelCourseIdToCourseScoreStats["{$score['__level']}.{$score['course_id']}"] = [
                    'last_highest_score' => $score['score'],
                    'rank' => 1
                ];
            }

            if ($hashMapLevelCourseIdToCourseScoreStats["{$score['__level']}.{$score['course_id']}"]['last_highest_score'] != $score['score'])
            {
                $hashMapLevelCourseIdToCourseScoreStats["{$score['__level']}.{$score['course_id']}"]['last_highest_score'] = $score['score'];
                $hashMapLevelCourseIdToCourseScoreStats["{$score['__level']}.{$score['course_id']}"]['rank']++;
            }

            if (!isset($hashMapClassIdCourseIdToClassCourseScoreStats["{$score['__class_id']}.{$score['course_id']}"]))
            {
                $hashMapClassIdCourseIdToClassCourseScoreStats["{$score['__class_id']}.{$score['course_id']}"] = [
                    'last_highest_score' => $score['score'],
                    'rank' => 1
                ];
            }

            if ($hashMapClassIdCourseIdToClassCourseScoreStats["{$score['__class_id']}.{$score['course_id']}"] != $score['score'])
            {
                $hashMapClassIdCourseIdToClassCourseScoreStats["{$score['__class_id']}.{$score['course_id']}"]['last_highest_score'] = $score['score'];
                $hashMapClassIdCourseIdToClassCourseScoreStats["{$score['__class_id']}.{$score['course_id']}"]['rank']++;
            }

            $hashMapStudentIdToStudentReportCard[$score['__student_id']]['scores']["{$score['__student_id']}.{$score['course_id']}"]['rank_in_class'] = $hashMapClassIdCourseIdToClassCourseScoreStats["{$score['__class_id']}.{$score['course_id']}"]['rank'];
            $hashMapStudentIdToStudentReportCard[$score['__student_id']]['scores']["{$score['__student_id']}.{$score['course_id']}"]['rank_in_level'] = $hashMapLevelCourseIdToCourseScoreStats["{$score['__level']}.{$score['course_id']}"]['rank'];
        }

        unset($studentReportCard);

        foreach ($hashMapStudentIdToStudentReportCard AS $arrayStudentReportCard)
        {
            $studentReportCard = StudentReportCardModel::query()->create($arrayStudentReportCard);

            foreach ($arrayStudentReportCard['scores'] AS &$studentReportCardScore)
            {
                $studentReportCardScore['student_report_card_id'] = $studentReportCard->id;
            }

            StudentReportCardScoreModel::query()->insert(array_values($arrayStudentReportCard));
        }

        $this->eloquent->update([
            'was_issued' => true
        ]);

        return true;
    }
}

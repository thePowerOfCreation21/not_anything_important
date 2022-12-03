<?php

namespace App\Actions;

use App\Http\Resources\ClassScoreResource;
use App\Models\ClassScoreModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassScoreAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassScoreModel::class)
            ->setResource(ClassScoreResource::class)
            ->setValidationRules([
                'store' => [
                    'class_course_id' => ['required', 'string', 'max:20'],
                    'date' => ['required', 'date_format:Y-m-d'],
                    'educational_year' => ['nullable', 'string', 'min:2', 'max:50'],
                    'max_score' => ['required', 'string', 'min:2', 'max:5000']
                ],
                'update' => [
                    'telegram' => ['string', 'min:2', 'max:5000']
                ]
            ]);
        parent::__construct();
    }
}

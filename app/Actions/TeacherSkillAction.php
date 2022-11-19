<?php

namespace App\Actions;

use App\Http\Resources\TeacherSkillResource;
use App\Models\TeacherSkillModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Http\Request;

class TeacherSkillAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(TeacherSkillModel::class)
            ->setResource(TeacherSkillResource::class)
            ->setValidationRules([
                'storeByAdmin' => [
                    'course_title' => ['required', 'string', 'max:350'],
                    'educational_institution' => ['string', 'max:350'],
                    'skill_level' => ['string', 'max:350']
                ],
                'update' => [
                    'course_title' => ['required', 'string', 'max:350'],
                    'educational_institution' => ['string', 'max:350'],
                    'skill_level' => ['string', 'max:350']
                ],
                'get_query' => [
                    'search' => ['string', 'max:255'],
                ]
        ]);

        parent::__construct();
    }


}

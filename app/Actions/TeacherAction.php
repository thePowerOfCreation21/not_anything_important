<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\TeacherResource;
use App\Models\TeacherModel;
use App\Models\TeacherSkillModel;
use App\Models\TeacherWorkExperienceModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherAction extends ActionService
{
    protected string $defaultRegisterStatus = 'pending';

    public function __construct()
    {
        $this
            ->setModel(TeacherModel::class)
            ->setValidationRules([
                'store' => [
                    'full_name' => ['required', 'string', 'max:150'],
                    'father_name' => ['nullable', 'string', 'max:150'],
                    'birth_certificate_number' => ['nullable', 'string', 'max:50'],
                    'national_id' => ['required', 'string', 'max:50', 'unique:teachers'],
                    'birth_certificate_location' => ['nullable', 'string', 'max:500'],
                    'birth_location' => ['nullable', 'string', 'max:500'],
                    'birth_date' => ['nullable', 'string', 'max:50'],
                    'degree_of_education' => ['nullable', 'string', 'max:500'],
                    'address' => ['nullable', 'string', 'max:500'],
                    'phone_number' => ['nullable', 'string', 'max:50'],
                    'file' => ['nullable', 'file', 'mimes:rar,zip,7zip,pdf', 'max:5000'],
                    'partner_full_name' => ['nullable', 'string', 'max:150'],
                    'partner_job' => ['nullable', 'string', 'max:150'],
                    'partner_birth_certificate_number' => ['nullable', 'string', 'max:50'],
                    'partner_national_id' => ['nullable', 'string', 'max:50'],
                    'partner_birth_certificate_location' => ['nullable', 'string', 'max:500'],
                    'partner_birth_location' => ['nullable', 'string', 'max:500'],
                    'partner_birth_date' => ['nullable', 'string', 'max:50'],
                    'partner_degree_of_education' => ['nullable', 'string', 'max:500'],
                    'partner_job_address' => ['nullable', 'string', 'max:500'],
                    'partner_phone_number' => ['nullable', 'string', 'max:50'],
                    'partner_emergency_call_number' => ['nullable', 'string', 'max:50'],
                    'partner_file' => ['nullable', 'file', 'mimes:rar,zip,7zip,pdf', 'max:5000'],
                    'is_married' => ['nullable', 'string', 'max:50'],
                    'password' => ['required', 'string', 'max:100'],


                    // work experiences
                    'workExperiences' => ['array', 'max:100'],
                    'workExperiences.*.title' => ['required', 'string', 'max:350'],
                    'workExperiences.*.workplace_name' => ['nullable', 'string', 'max:350'],
                    'workExperiences.*.work_title' => ['nullable', 'string', 'max:350'],
                    'workExperiences.*.current_status' => ['nullable', 'string', 'max:350'],
                    'workExperiences.*.reason_for_the_termination_of_cooperation' => ['nullable', 'string', 'max:350'],
                    'workExperiences.*.workplace_location' => ['nullable', 'string', 'max:350'],

                    // skills
                    'skills' => ['array', 'max:100'],
                    'skills.*.course_title' => ['required', 'string', 'max:350'],
                    'skills.*.educational_institution' => ['nullable', 'string', 'max:350'],
                    'skills.*.skill_level' => ['nullable', 'string', 'max:350']
                ],
            ])
            ->setCasts([
                'file' => ['file', 'nullable'],
                'partner_file' => ['file', 'nullable']
            ])
            ->setResource(TeacherResource::class);

        parent::__construct();
    }

    /**
     * @param string $defaultRegisterStatus
     * @return $this
     */
    public function setDefaultRegisterStatus (string $defaultRegisterStatus): static
    {
        if (in_array($defaultRegisterStatus, $this->getAllowedRegisterStatuses()))
        {
            $this->defaultRegisterStatus = $defaultRegisterStatus;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultRegisterStatus (): string
    {
        return $this->defaultRegisterStatus;
    }

    /**
     * @return string[]
     */
    public function getAllowedRegisterStatuses (): array
    {
        return ['added_by_admin', 'accepted', 'rejected', 'pending'];
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $fieldName
     * @return string
     */
    protected function uploadFile(UploadedFile $file, string $path = '/uploads', string $fieldName = null): string
    {
        if (empty($path))
        {
            $path = '/uploads';
        }

        $path = "$path/" . base64_encode(Str::random(32));

        return $file->storeAs($path, $file->getClientOriginalName());
    }

    public function store(array $data, callable $storing = null): mixed
    {
        $data['register_status'] = $data['register_status'] ?? $this->getDefaultRegisterStatus();

        $data['educational_year'] = $data['educational_year'] ?? PardisanHelper::getCurrentEducationalYear();

        $data['password'] = Hash::make($data['password']);

        $teacher = parent::store($data, $storing);

        $this->storeTeacherWorkExperiences($teacher->id, $data['workExperiences'] ?? []);

        $this->storeTeacherSkills($teacher->id, $data['skills'] ?? []);

        return $teacher;
    }

    /**
     * @param string $teacherId
     * @param array $workExperiences
     * @return mixed
     */
    public function storeTeacherWorkExperiences (string $teacherId, array $workExperiences): mixed
    {
        foreach ($workExperiences AS $key => $workExperience)
        {
            $workExperiences[$key]['teacher_id'] = $teacherId;
        }

        return empty($workExperiences) ? false : TeacherWorkExperienceModel::insert($workExperiences);
    }

    /**
     * @param string $teacherId
     * @param array $skills
     * @return mixed
     */
    public function storeTeacherSkills (string $teacherId, array $skills): mixed
    {
        foreach ($skills AS $key => $skill)
        {
            $skills[$key]['teacher_id'] = $teacherId;
        }

        return empty($skills) ? false : TeacherSkillModel::insert($skills);
    }
}

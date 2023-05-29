<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\TeacherResource;
use App\Imports\TeacherSkillsImport;
use App\Imports\TeacherWorkExperiencesImport;
use App\Models\TeacherModel;
use App\Models\TeacherSkillModel;
use App\Models\TeacherWorkExperienceModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

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
                    'national_id' => ['required', 'string', 'max:50'],
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
                    'educational_level' => ['nullable', 'string', 'max:150'],
                    'partner_educational_level' => ['nullable', 'string', 'max:150'],
                    'birth_place' => ['nullable', 'string', 'max:250'],
                    'partner_birth_place' => ['nullable', 'string', 'max:250'],
                    'password' => ['required', 'string', 'max:100'],
                    'educational_year' => ['string', 'max:50'],
                    'work_experiences_file' => ['nullable', 'file', 'mimes:xlx,xls,xlsx', 'max:2048'],
                    'skills_file' => ['nullable', 'file', 'mimes:xlx,xls,xlsx', 'max:2048'],


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
                'update' => [
                    'full_name' => ['string', 'max:150'],
                    'father_name' => ['nullable', 'string', 'max:150'],
                    'birth_certificate_number' => ['nullable', 'string', 'max:50'],
                    'national_id' => ['string', 'max:50'],
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
                    'educational_level' => ['nullable', 'string', 'max:150'],
                    'partner_educational_level' => ['nullable', 'string', 'max:150'],
                    'birth_place' => ['nullable', 'string', 'max:250'],
                    'partner_birth_place' => ['nullable', 'string', 'max:250'],
                    'password' => ['string', 'max:100'],
                    'educational_year' => ['string', 'max:50'],
                ],
                'getQuery' => [
                    'class_id' => ['string', 'max:20'],
                    'register_status' => ['string', 'max:150'],
                    'educational_year' => ['string', 'max:50'],
                    'from_created_at' => ['date_format:Y-m-d'],
                    'to_created_at' => ['date_format:Y-m-d'],
                    'search' => ['string', 'max:150'],
                    'week_day' => ['between:1,7']
                ],
                'login' => [
                    'national_id' => ['required', 'string', 'max:50'],
                    'password' => ['required', 'string', 'max:100']
                ],
                'changePassword' => [
                    'current_password' => ['required', 'string', 'max:100'],
                    'new_password' => ['required', 'string', 'max:100']
                ]
            ])
            ->setCasts([
                'file' => ['file', 'nullable'],
                'partner_file' => ['file', 'nullable']
            ])
            ->setQueryToEloquentClosures([
                'class_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classCourses', function ($q) use ($query){
                        $q->where('class_id', $query['class_id']);
                    });
                },
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'register_status' => function (&$eloquent, $query)
                {
                    $query['register_status'] = is_string($query['register_status']) ? explode(',', $query['register_status']) : $query['register_status'];

                    $eloquent = $eloquent->whereIn('register_status', $query['register_status']);
                },
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('full_name', 'LIKE', "%{$query['search']}%");
                },
                'from_created_at' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('created_at', '>=', $query['from_created_at']);
                },
                'to_created_at' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('created_at', '<=', $query['to_created_at']);
                },
                'week_day' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('entrances', function ($q) use($query){
                        $q->where('week_day', $query['week_day']);
                    });
                },
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

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws CustomException
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $data['register_status'] = $data['register_status'] ?? $this->getDefaultRegisterStatus();

        $data['educational_year'] = $data['educational_year'] ?? PardisanHelper::getCurrentEducationalYear();

        $data['password'] = Hash::make($data['password']);

        if (TeacherModel::query()->where('national_id', $data['national_id'])->exists())
        {
            throw new CustomException('this national_id is already taken', 986585);
        }

        $teacher = parent::store($data, $storing);

        if (isset($data['skills_file']))
            Excel::import(new TeacherSkillsImport($teacher->id), $data['skills_file']);

        if (isset($data['work_experiences_file']))
            Excel::import(new TeacherWorkExperiencesImport($teacher->id), $data['work_experiences_file']);

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

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     * @throws CustomException
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        $updating = function ($eloquent, &$updateData) use ($updating)
        {
            foreach ($eloquent->get() AS $entity)
            {
                if (array_key_exists('file', $updateData) && is_file($entity->file))
                {
                    unlink($entity->file);
                }

                if (array_key_exists('partner_file', $updateData) && is_file($entity->partner_file))
                {
                    unlink($entity->partner_file);
                }

                if (isset($updateData['national_id']) && TeacherModel::query()->where('id', '!=', $entity->id)->where('national_id', $updateData['national_id'])->exists())
                {
                    throw new CustomException('national id already taken', 2100, 986585);
                }
            }
            if (is_callable($updating))
            {
                $updating($eloquent, $updateData);
            }
        };

        return parent::update($updateData, $updating);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function acceptById (string $id): mixed
    {
        return TeacherModel::where('id', $id)
            ->update([
                'register_status' => 'accepted',
            ]);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function rejectById (string $id): mixed
    {
        return TeacherModel::where('id', $id)
            ->update([
                'register_status' => 'rejected',
            ]);
    }

    /**
     * @return array
     * @throws CustomException
     * @throws Throwable
     */
    #[ArrayShape(['token' => "mixed"])]
    public function loginByRequest (): array
    {
        return $this->login(
            $this->setValidationRule('login')->getDataFromRequest()
        );
    }

    /**
     * @param array $data
     * @return array
     * @throws Throwable
     */
    #[ArrayShape(['token' => "mixed"])]
    public function login (array $data): array
    {
        $teacher = TeacherModel::query()->where('national_id', $data['national_id'])->firstOrFail();

        throw_if(! Hash::check($data['password'], $teacher->password), CustomException::class, 'password is wrong', '84054', 400);

        return [
            'token' => $teacher->createToken('auth')->plainTextToken
        ];
    }

    /**
     * @return bool
     * @throws CustomException
     * @throws Throwable
     */
    public function changePasswordByRequest (): bool
    {
        return $this->changePassword(
            $this->setValidationRule('changePassword')->getDataFromRequest(),
            $this->request->user()
        );
    }

    /**
     * @param array $data
     * @param TeacherModel $teacher
     * @return bool
     * @throws Throwable
     */
    public function changePassword (array $data, TeacherModel $teacher): bool
    {
        throw_if(! Hash::check($data['current_password'], $teacher->password), CustomException::class, 'current_password is wrong', '978244', 400);

        $teacher->password = Hash::make($data['new_password']);
        return $teacher->save();
    }
}

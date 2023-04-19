<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\StudentResource;
use App\Models\StudentModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Morilog\Jalali\CalendarUtils;
use Throwable;

class StudentAction extends ActionService
{
    protected string $defaultRegisterStatus = 'pending';

    public function __construct()
    {
        $allowedRegisterStatusesString = implode(',', $this->getAllowedRegisterStatuses());

        $this
            ->setModel(StudentModel::class)
            ->setResource(StudentResource::class)
            ->setValidationRules([
                'storeByAdmin' => [
                    'first_name' => ['required', 'string', 'max:150'],
                    'last_name' => ['nullable', 'string', 'max:150'],
                    'meli_code' => ['required', 'string', 'max:25'],
                    'birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_serial_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'birth_location' => ['nullable', 'string', 'max:1500'],
                    'birth_date' => ['nullable', 'string', 'max:150'],
                    'birth_place' => ['nullable', 'string', 'max:250'],
                    'nationality' => ['nullable', 'string', 'max:150'],
                    'religion' => ['nullable', 'string', 'max:150'],
                    'religion_orientation' => ['nullable', 'string', 'max:150'],
                    'illness_record' => ['nullable', 'string', 'max:2500'],
                    'medicine_in_use' => ['nullable', 'string', 'max:2500'],
                    'family_child_number' => ['nullable', 'string', 'max:100'],
                    'all_family_children_count' => ['nullable', 'string', 'max:100'],
                    'is_disabled' => ['nullable', 'string', 'max:150'],
                    'divorced_parents' => ['nullable', 'string', 'max:150'],
                    'dominant_hand' => ['nullable', 'string', 'max:150'],
                    'living_with' => ['nullable', 'string', 'max:150'],
                    'address' => ['nullable', 'string', 'max:1500'],
                    'mobile_number' => ['required', 'string', 'max:50'],
                    'phone_number' => ['nullable', 'string', 'max:50'],
                    'phone_number_of_close_relative' => ['nullable', 'string', 'max:50'],
                    'father_first_name' => ['nullable', 'string', 'max:150'],
                    'father_last_name' => ['nullable', 'string', 'max:150'],
                    'father_father_name' => ['nullable', 'string', 'max:150'],
                    'father_birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'father_birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'father_birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'father_birth_location' => ['nullable', 'string', 'max:1500'],
                    'father_birth_date' => ['nullable', 'string', 'max:50'],
                    'father_nationality' => ['nullable', 'string', 'max:150'],
                    'father_religion' => ['nullable', 'string', 'max:150'],
                    'father_religion_orientation' => ['nullable', 'string', 'max:150'],
                    'father_meli_code' => ['nullable', 'string', 'max:25'],
                    'father_education' => ['nullable', 'string', 'max:100'],
                    'father_job' => ['nullable', 'string', 'max:150'],
                    'father_health_status' => ['nullable', 'string', 'max:1500'],
                    'father_mobile_number' => ['nullable', 'string', 'max:50'],
                    'father_work_address' => ['nullable', 'string', 'max:1500'],
                    'father_file' => ['nullable', 'file', 'mimes:zip,rar,pdf,jpg,jpeg,png,svg', 'max:5000'],
                    'father_educational_level' => ['nullable', 'string', 'max:150'],
                    'mother_first_name' => ['nullable', 'string', 'max:150'],
                    'mother_last_name' => ['nullable', 'string', 'max:150'],
                    'mother_father_name' => ['nullable', 'string', 'max:150'],
                    'mother_birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_serial_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'mother_birth_location' => ['nullable', 'string', 'max:1500'],
                    'mother_birth_date' => ['nullable', 'string', 'max:50'],
                    'mother_nationality' => ['nullable', 'string', 'max:150'],
                    'mother_religion' => ['nullable', 'string', 'max:150'],
                    'mother_religion_orientation' => ['nullable', 'string', 'max:150'],
                    'mother_meli_code' => ['nullable', 'string', 'max:25'],
                    'mother_education' => ['nullable', 'string', 'max:100'],
                    'mother_job' => ['nullable', 'string', 'max:150'],
                    'mother_health_status' => ['nullable', 'string', 'max:1500'],
                    'mother_mobile_number' => ['nullable', 'string', 'max:50'],
                    'mother_work_address' => ['nullable', 'string', 'max:1500'],
                    'mother_file' => ['nullable', 'file', 'mimes:zip,rar,pdf,jpg,jpeg,png,svg', 'max:5000'],
                    'mother_educational_level' => ['nullable', 'string', 'max:150'],
                    'non_contagious_illness' => ['nullable', 'string', 'max:2500'],
                    'mental_illness' => ['nullable', 'string', 'max:2500'],
                    'level' => ['nullable', 'string', 'max:100'],
                    'file' => ['nullable', 'file', 'mimes:zip,rar,pdf,png,jpg,jpeg,svg', 'max:5000'],
                    'report_card_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:2000'],
                    'educational_year' => ['string', 'max:25'],
                    'password' => ['required', 'string', 'max:100'],
                    // 'register_status' => ['in:' . $allowedRegisterStatusesString]
                ],
                'updateByAdmin' => [
                    'first_name' => ['string', 'max:150'],
                    'last_name' => ['nullable', 'string', 'max:150'],
                    'meli_code' => ['string', 'max:25'],
                    'birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_serial_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'birth_location' => ['nullable', 'string', 'max:1500'],
                    'birth_date' => ['nullable', 'string', 'max:150'],
                    'birth_place' => ['nullable', 'string', 'max:250'],
                    'nationality' => ['nullable', 'string', 'max:150'],
                    'religion' => ['nullable', 'string', 'max:150'],
                    'religion_orientation' => ['nullable', 'string', 'max:150'],
                    'illness_record' => ['nullable', 'string', 'max:2500'],
                    'medicine_in_use' => ['nullable', 'string', 'max:2500'],
                    'family_child_number' => ['nullable', 'string', 'max:100'],
                    'all_family_children_count' => ['nullable', 'string', 'max:100'],
                    'is_disabled' => ['nullable', 'string', 'max:150'],
                    'divorced_parents' => ['nullable', 'string', 'max:150'],
                    'dominant_hand' => ['nullable', 'string', 'max:150'],
                    'living_with' => ['nullable', 'string', 'max:150'],
                    'address' => ['nullable', 'string', 'max:1500'],
                    'mobile_number' => ['string', 'max:50'],
                    'phone_number' => ['nullable', 'string', 'max:50'],
                    'phone_number_of_close_relative' => ['nullable', 'string', 'max:50'],
                    'father_first_name' => ['nullable', 'string', 'max:150'],
                    'father_last_name' => ['nullable', 'string', 'max:150'],
                    'father_father_name' => ['nullable', 'string', 'max:150'],
                    'father_birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'father_birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'father_birth_certificate_serial_number' => ['nullable', 'string', 'max:25'],
                    'father_birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'father_birth_location' => ['nullable', 'string', 'max:1500'],
                    'father_birth_date' => ['nullable', 'string', 'max:50'],
                    'father_nationality' => ['nullable', 'string', 'max:150'],
                    'father_religion' => ['nullable', 'string', 'max:150'],
                    'father_religion_orientation' => ['nullable', 'string', 'max:150'],
                    'father_meli_code' => ['nullable', 'string', 'max:25'],
                    'father_education' => ['nullable', 'string', 'max:100'],
                    'father_job' => ['nullable', 'string', 'max:150'],
                    'father_health_status' => ['nullable', 'string', 'max:1500'],
                    'father_mobile_number' => ['nullable', 'string', 'max:50'],
                    'father_work_address' => ['nullable', 'string', 'max:1500'],
                    'father_file' => ['nullable', 'file', 'mimes:zip,rar,pdf,jpg,jpeg,png,svg', 'max:5000'],
                    'father_educational_level' => ['nullable', 'string', 'max:150'],
                    'mother_first_name' => ['nullable', 'string', 'max:150'],
                    'mother_last_name' => ['nullable', 'string', 'max:150'],
                    'mother_father_name' => ['nullable', 'string', 'max:150'],
                    'mother_birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_serial_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'mother_birth_location' => ['nullable', 'string', 'max:1500'],
                    'mother_birth_date' => ['nullable', 'string', 'max:50'],
                    'mother_nationality' => ['nullable', 'string', 'max:150'],
                    'mother_religion' => ['nullable', 'string', 'max:150'],
                    'mother_religion_orientation' => ['nullable', 'string', 'max:150'],
                    'mother_meli_code' => ['nullable', 'string', 'max:25'],
                    'mother_education' => ['nullable', 'string', 'max:100'],
                    'mother_job' => ['nullable', 'string', 'max:150'],
                    'mother_health_status' => ['nullable', 'string', 'max:1500'],
                    'mother_mobile_number' => ['nullable', 'string', 'max:50'],
                    'mother_work_address' => ['nullable', 'string', 'max:1500'],
                    'mother_file' => ['nullable', 'file', 'mimes:zip,rar,pdf,jpg,jpeg,png,svg', 'max:5000'],
                    'mother_educational_level' => ['nullable', 'string', 'max:150'],
                    'non_contagious_illness' => ['nullable', 'string', 'max:2500'],
                    'mental_illness' => ['nullable', 'string', 'max:2500'],
                    'level' => ['nullable', 'string', 'max:100'],
                    'file' => ['nullable', 'file', 'mimes:zip,rar,pdf,jpg,jpeg,png,svg', 'max:5000'],
                    'report_card_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:2000'],
                    'educational_year' => ['string', 'max:25'],
                    'password' => ['string', 'max:100'],
                    // 'register_status' => ['in:' . $allowedRegisterStatusesString]
                ],
                'registerRequest' => [
                    'first_name' => ['required', 'string', 'max:150'],
                    'last_name' => ['required', 'string', 'max:150'],
                    'meli_code' => ['required', 'string', 'max:25'],
                    'birth_certificate_number' => ['required', 'string', 'max:25'],
                    'birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_serial_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'birth_location' => ['nullable', 'string', 'max:1500'],
                    'birth_date' => ['required', 'string', 'max:150'],
                    'birth_place' => ['required', 'string', 'max:250'],
                    'nationality' => ['nullable', 'string', 'max:150'],
                    'religion' => ['nullable', 'string', 'max:150'],
                    'religion_orientation' => ['nullable', 'string', 'max:150'],
                    'illness_record' => ['nullable', 'string', 'max:2500'],
                    'medicine_in_use' => ['nullable', 'string', 'max:2500'],
                    'family_child_number' => ['nullable', 'string', 'max:100'],
                    'all_family_children_count' => ['nullable', 'string', 'max:100'],
                    'is_disabled' => ['nullable', 'string', 'max:150'],
                    'divorced_parents' => ['nullable', 'string', 'max:150'],
                    'dominant_hand' => ['nullable', 'string', 'max:150'],
                    'living_with' => ['nullable', 'string', 'max:150'],
                    'address' => ['nullable', 'string', 'max:1500'],
                    'mobile_number' => ['required', 'string', 'max:50'],
                    'phone_number' => ['nullable', 'string', 'max:50'],
                    'phone_number_of_close_relative' => ['nullable', 'string', 'max:50'],
                    'father_first_name' => ['required', 'string', 'max:150'],
                    'father_last_name' => ['required', 'string', 'max:150'],
                    'father_father_name' => ['nullable', 'string', 'max:150'],
                    'father_birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'father_birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'father_birth_certificate_serial_number' => ['nullable', 'string', 'max:25'],
                    'father_birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'father_birth_location' => ['nullable', 'string', 'max:1500'],
                    'father_birth_date' => ['nullable', 'string', 'max:50'],
                    'father_nationality' => ['nullable', 'string', 'max:150'],
                    'father_religion' => ['nullable', 'string', 'max:150'],
                    'father_religion_orientation' => ['nullable', 'string', 'max:150'],
                    'father_meli_code' => ['nullable', 'string', 'max:25'],
                    'father_education' => ['nullable', 'string', 'max:100'],
                    'father_job' => ['nullable', 'string', 'max:150'],
                    'father_health_status' => ['nullable', 'string', 'max:1500'],
                    'father_mobile_number' => ['required', 'string', 'max:50'],
                    'father_work_address' => ['nullable', 'string', 'max:1500'],
                    'father_file' => ['nullable', 'file', 'mimes:zip,rar,pdf,jpg,jpeg,png,svg', 'max:5000'],
                    'father_educational_level' => ['required', 'string', 'max:150'],
                    'mother_first_name' => ['nullable', 'string', 'max:150'],
                    'mother_last_name' => ['nullable', 'string', 'max:150'],
                    'mother_father_name' => ['nullable', 'string', 'max:150'],
                    'mother_birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_serial_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'mother_birth_location' => ['nullable', 'string', 'max:1500'],
                    'mother_birth_date' => ['nullable', 'string', 'max:50'],
                    'mother_nationality' => ['nullable', 'string', 'max:150'],
                    'mother_religion' => ['nullable', 'string', 'max:150'],
                    'mother_religion_orientation' => ['nullable', 'string', 'max:150'],
                    'mother_meli_code' => ['nullable', 'string', 'max:25'],
                    'mother_education' => ['nullable', 'string', 'max:100'],
                    'mother_job' => ['nullable', 'string', 'max:150'],
                    'mother_health_status' => ['nullable', 'string', 'max:1500'],
                    'mother_mobile_number' => ['required', 'string', 'max:50'],
                    'mother_work_address' => ['nullable', 'string', 'max:1500'],
                    'mother_file' => ['nullable', 'file', 'mimes:zip,rar,pdf,jpg,jpeg,png,svg', 'max:5000'],
                    'mother_educational_level' => ['required', 'string', 'max:150'],
                    'non_contagious_illness' => ['nullable', 'string', 'max:2500'],
                    'mental_illness' => ['nullable', 'string', 'max:2500'],
                    'level' => ['required', 'string', 'max:100'],
                    'file' => ['nullable', 'file', 'mimes:zip,rar,pdf,png,jpg,jpeg,svg', 'max:5000'],
                    'report_card_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:2000'],
                    // 'educational_year' => ['string', 'max:25'],
                    'password' => ['required', 'string', 'max:100'],
                    // 'register_status' => ['in:' . $allowedRegisterStatusesString]
                ],
                'block' => [
                    'reason' => ['nullable', 'string', 'max:2500']
                ],
                'getQuery' => [
                    'class_id' => ['string', 'max:20'],
                    'level' => ['string', 'max:100'],
                    'educational_year' => ['string', 'max:50'],
                    'search' => ['string', 'max:150'],
                    'register_status' => ['string', 'max:150'],
                    'from_created_at' => ['date_format:Y-m-d'],
                    'to_created_at' => ['date_format:Y-m-d'],
                ],
                'login' => [
                    'meli_code' => ['required', 'string', 'max:25'],
                    'password' => ['required', 'string', 'max:100']
                ],
                'sendOtp' => [
                    'mobile_number' => ['required', 'string', 'max:50']
                ],
                'checkOtp' => [
                    'mobile_number' => ['required', 'string', 'max:50'],
                    'otp' => ['required', 'string', 'max:6']
                ],
                'changePassword' => [
                    'current_password' => ['string', 'max:100'],
                    'new_password' => ['required', 'string', 'max:100']
                ]
            ])
            ->setCasts([
                'file' => ['nullable', 'file'],
                'father_file' => ['nullable', 'file'],
                'mother_file' => ['nullable', 'file'],
                'report_card_pdf' => ['nullable', 'file'],
                'from_created_at' => ['jalali_to_gregorian:Y-m-d'],
                'to_created_at' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'class_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classes', function($q) use ($query){
                        $q->where('classes.id', $query['class_id']);
                    });
                },
                'level' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('level', $query['level']);
                },
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('full_name', 'LIKE', "%{$query['search']}%");
                },
                'register_status' => function (&$eloquent, $query)
                {
                    $query['register_status'] = is_string($query['register_status']) ? explode(',', $query['register_status']) : $query['register_status'];

                    $eloquent = $eloquent->whereIn('register_status', $query['register_status']);
                },
                'from_created_at' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('created_at', '>=', $query['from_created_at']);
                },
                'to_created_at' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('created_at', '<=', $query['to_created_at']);
                }
            ]);

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
        $path = '/uploads/rfrf';

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
        if (StudentModel::where('meli_code', $data['meli_code'])->exists())
        {
            throw new CustomException('this meli_code is already taken', 1000);
        }

        $data['full_name'] = $data['first_name'] . ' ' . @$data['last_name'];

        if (! isset($data['register_status']))
        {
            $data['register_status'] = $this->getDefaultRegisterStatus();
        }

        if (! isset($data['educational_year']))
        {
            $data['educational_year'] = PardisanHelper::getCurrentEducationalYear();
        }

        $data['password'] = Hash::make($data['password']);

        return parent::store($data, $storing);
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
            $entity = $this->getFirstByEloquent($eloquent);

            if (StudentModel::where('id', '!=', $entity->id)->where('meli_code', $updateData['meli_code'])->exists())
            {
                throw new CustomException('this meli_code is already taken', 1000);
            }

            $updateData['full_name'] = ($updateData['first_name'] ?? $entity->first_name) . ' ' . ($updateData['last_name'] ?? $entity->last_name);

            if (array_key_exists('file', $updateData) && is_file($entity->file))
            {
                unlink($entity->file);
            }

            if (array_key_exists('report_card_pdf', $updateData) && is_file($entity->report_card_pdf))
            {
                unlink($entity->report_card_pdf);
            }

            if (isset($updateData['password']))
            {
                $updateData['password'] = Hash::make($updateData['password']);
            }

            if (is_callable($updating))
            {
                $updating($eloquent, $updateData);
            }
        };

        return parent::update($updateData, $updating);
    }

    /**
     * @param string $studentId
     * @return mixed
     * @throws CustomException
     */
    public function blockByRequest (string $studentId): mixed
    {
        $data = $this->getDataFromRequest();

        return StudentModel::where('id', $studentId)
            ->update([
                'is_block' => true,
                'reason_for_blocking' => @$data['reason']
            ]);
    }

    /**
     * @param string $studentId
     * @return mixed
     */
    public function unblock (string $studentId): mixed
    {
        return StudentModel::where('id', $studentId)
            ->update([
                'is_block' => false,
                'reason_for_blocking' => null
            ]);
    }

    /**
     * @param string $studentId
     * @return mixed
     */
    public function acceptById (string $studentId): mixed
    {
        return StudentModel::where('id', $studentId)
            ->update([
                'register_status' => 'accepted',
            ]);
    }

    /**
     * @param string $studentId
     * @return mixed
     */
    public function rejectById (string $studentId): mixed
    {
        return StudentModel::where('id', $studentId)
            ->update([
                'register_status' => 'rejected',
            ]);
    }

    /**
     * @param StudentModel|Model $student
     * @return array
     */
    #[ArrayShape(['student' => "mixed", 'token' => "mixed"])]
    protected function getLoginInfoByStudent (StudentModel|Model $student): array
    {
        return [
            'student' => $this->applyResourceToEntity($student),
            'token' => $student->createToken('auth')->plainTextToken
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws CustomException
     */
    #[ArrayShape(['student' => "mixed", 'token' => "mixed"])]
    public function login (array $data): array
    {
        $student = StudentModel::query()->where('meli_code', $data['meli_code'])->first();

        if (!empty($student) && Hash::check($data['password'], $student->password))
        {
            return $this->getLoginInfoByStudent($student);
        }

        throw new CustomException('login info was wrong', '513540', 400);
    }

    /**
     * @return array
     * @throws CustomException
     */
    #[ArrayShape(['student' => "mixed", 'token' => "mixed"])]
    public function loginByRequest (): array
    {
        return $this->login($this->getDataFromRequest());
    }

    /**
     * @param StudentModel|Model $student
     * @return StudentModel
     * @throws Throwable
     */
    public function sendOtp (StudentModel|Model $student): StudentModel
    {
        throw_if($student->otp_expires_at > time(), CustomException::class, 'otp already sent', '16562', 400);

        $otp = random_int(111111, 999999);

        // TODO: send sms
        Log::info("OTP: $otp");

        $student->otp_expires_at = time() + 120;
        $student->otp = Hash::make($otp);
        $student->save();

        return $student;
    }

    /**
     * @return StudentModel
     * @throws CustomException
     * @throws Throwable
     */
    public function sendOtpByRequest (): StudentModel
    {
        $data = $this->getDataFromRequest();

        return $this->sendOtp(StudentModel::query()->where('mobile_number', $data['mobile_number'])->firstOrFail());
    }

    /**
     * @param array $data
     * @return array
     * @throws Throwable
     */
    #[ArrayShape(['student' => "mixed", 'token' => "mixed"])]
    public function checkOtp (array $data): array
    {
        $student = StudentModel::query()->where('mobile_number', $data['mobile_number'])->firstOrFail();

        throw_if($student->otp_expires_at < time() || !Hash::check($data['otp'], $student->otp), CustomException::class, 'otp is wrong or expired', '293770', 400);

        $student->should_change_password = true;
        $student->save();

        return $this->getLoginInfoByStudent($student);
    }

    /**
     * @return array
     * @throws CustomException
     * @throws Throwable
     */
    #[ArrayShape(['student' => "mixed", 'token' => "mixed"])]
    public function checkOtpByRequest (): array
    {
        return $this->checkOtp($this->getDataFromRequest());
    }

    /**
     * @param StudentModel|Model $student
     * @param array $data
     * @return Model|StudentModel
     * @throws Throwable
     */
    public function changePassword (StudentModel|Model $student, array $data): Model|StudentModel
    {
        throw_if(
            !$student->should_change_password && (!isset($data['current_password']) || !Hash::check($data['current_password'], $student->password)),
            CustomException::class, 'current password should be set and should be equal to current password of student', '923686', 400
        );

        $student->should_change_password = false;
        $student->password = Hash::make($data['new_password']);
        $student->save();

        return $student;
    }

    /**
     * @return Model|StudentModel
     * @throws CustomException
     * @throws Throwable
     */
    public function changePasswordByRequest (): Model|StudentModel
    {
        return $this->changePassword($this->request->user(), $this->getDataFromRequest());
    }
}

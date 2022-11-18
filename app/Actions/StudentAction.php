<?php

namespace App\Actions;

use App\Models\StudentModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class StudentAction extends ActionService
{
    protected string $defaultRegisterStatus = 'pending';

    public function __construct()
    {
        $allowedRegisterStatusesString = implode(',', $this->getAllowedRegisterStatuses());

        $this
            ->setModel(StudentModel::class)
            ->setValidationRules([
                'storeByAdmin' => [
                    'first_name' => ['required', 'string', 'max:150'],
                    'last_name' => ['nullable', 'string', 'max:150'],
                    'meli_code' => ['required', 'string', 'max:25'],
                    'birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
                    'birth_certificate_issued_location' => ['nullable', 'string', 'max:1500'],
                    'birth_location' => ['nullable', 'string', 'max:1500'],
                    'birth_date' => ['nullable', 'string', 'max:1500'],
                    'nationality' => ['nullable', 'string', 'max:150'],
                    'religion' => ['nullable', 'string', 'max:150'],
                    'religion_orientation' => ['nullable', 'string', 'max:150'],
                    'illness_record' => ['nullable', 'string', 'max:2500'],
                    'medicine_in_us' => ['nullable', 'string', 'max:2500'],
                    'family_child_number' => ['nullable', 'string', 'max:100'],
                    'all_family_children_count' => ['nullable', 'string', '100'],
                    'is_disabled' => ['nullable', 'string', 'max:150'],
                    'divorced_parents' => ['nullable', 'string', 'max:150'],
                    'dominant_hand' => ['nullable', 'string', 'max:150'],
                    'living_with' => ['nullable', 'string', 'max:150'],
                    'address' => ['nullable', 'string', 'max:1500'],
                    'mobile_number' => ['nullable', 'string', 'max:50'],
                    'phone_number' => ['nullable', 'string', 'max:50'],
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
                    'mother_first_name' => ['nullable', 'string', 'max:150'],
                    'mother_last_name' => ['nullable', 'string', 'max:150'],
                    'mother_father_name' => ['nullable', 'string', 'max:150'],
                    'mother_birth_certificate_number' => ['nullable', 'string', 'max:25'],
                    'mother_birth_certificate_serie_number' => ['nullable', 'string', 'max:25'],
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
                    'non_contagious_illness' => ['nullable', 'string', 'max:2500'],
                    'mental_illness' => ['nullable', 'string', 'max:2500'],
                    'level' => ['nullable', 'string', 'max:100'],
                    'file' => ['nullable', 'file', 'mimes:zip,rar,pdf', 'max:5000'],
                    'report_card_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:2000'],
                    'educational_year' => ['required', 'string', 'max:25'],
                    'password' => ['required', 'string', 'max:100'],
                    // 'register_status' => ['in:' . $allowedRegisterStatusesString]
                ]
            ])
            ->setCasts([
                'file' => ['nullable', 'file'],
                'report_card_pdf' => ['nullable', 'file']
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

        return parent::store($data, $storing);
    }
}

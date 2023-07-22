<?php

namespace App\Imports;

use App\Helpers\PardisanHelper;
use App\Models\TeacherModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeachersImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row[2] = str_replace('-', '', $row[2] ?? 'null_imported');

        return new TeacherModel([
            'full_name' => "{$row[0]}  {$row[1]}",
            'national_id' => $row[2],
            'birth_certificate_number' => $row[3],
            'birth_certificate_location' => $row[5],
            'birth_date' => $row[6],
            'degree_of_education' => $row[7],
            'is_married' => $row[8],
            'phone_number' => '0' . $row[9],
            'address' => $row[11],
            'partner_full_name' => "{$row[12]}  {$row[13]}",
            'partner_degree_of_education' => $row[14],
            'partner_job' => $row[15],
            'partner_job_address' => $row[16],
            'register_status' => 'accepted',
            'password' => Hash::make($row[2]),
            'educational_year' => PardisanHelper::getCurrentEducationalYear()
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}

<?php

namespace App\Imports;

use App\Helpers\PardisanHelper;
use App\Models\StudentModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentsImport3 implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row[0] = str_replace('-', '', $row[0] ?? 'null_imported');

        return new StudentModel([
            'meli_code' => $row[0],
            'first_name' => $row[1] ?? 'null_imported',
            'last_name' => $row[2] ?? 'null_imported',
            'full_name' => $row[1] . ' ' . $row[2],
            'father_first_name' => $row[3],
            'father_education' => $row[4],
            'mother_education' => $row[5],
            'father_job' => $row[6],
            'mother_job' => $row[7],
            'mother_mobile_number' => '0' . $row[8],
            'father_mobile_number' => '0' . $row[9],
            'phone_number' => $row[10],
            'birth_date' => $row[12],
            'dominant_hand' => $row[13],
            'address' => $row[14],
            'password' => Hash::make($row[0]),
            'register_status' => 'accepted',
            'educational_year' => PardisanHelper::getCurrentEducationalYear()
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}

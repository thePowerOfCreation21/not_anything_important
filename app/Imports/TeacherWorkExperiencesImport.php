<?php

namespace App\Imports;

use App\Models\TeacherWorkExperienceModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeacherWorkExperiencesImport implements ToModel, WithStartRow
{
    protected string|null $teacher_id = null;

    public function __construct(string $teacher_id)
    {
        $this->teacher_id = $teacher_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TeacherWorkExperienceModel([
            'teacher_id' => $this->teacher_id,
            'title' => $row[0],
            'workplace_name' => $row[1],
            'work_title' => $row[2],
            'current_status' => $row[3],
            'reason_for_the_termination_of_cooperation' => $row[4],
            'workplace_location' => $row[5],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}

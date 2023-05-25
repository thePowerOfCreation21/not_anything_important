<?php

namespace App\Imports;

use App\Models\TeacherSkillModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeacherSkillsImport implements ToModel, WithStartRow
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
        return new TeacherSkillModel([
            'teacher_id' => $this->teacher_id,
            'course_title' => $row[0],
            'educational_institution' => $row[1],
            'skill_level' => $row[2]
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}

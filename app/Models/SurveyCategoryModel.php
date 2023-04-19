<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyCategoryModel extends Model
{
    use HasFactory;

    protected $table = 'survey_categories';

    protected $fillable = [
        'text',
        'participants_count',
        'is_active',
        'teacher_id'
    ];

    public function scopeForStudent($q, string $studentId)
    {
        $q
            ->whereNull('teacher_id')
            ->orWhere(function ($q) use($studentId){
                $q->whereHas('teacher', function($q) use($studentId){
                    $q->whereHas('classCourses', function($q) use($studentId){
                        $q->whereHas('classModel', function($q) use($studentId){
                            $q->whereHas('students', function($q) use($studentId){
                                $q->where('id', $studentId);
                            });
                        });
                    });
                });
            });
    }

    public function surveys (): HasMany
    {
        return $this->hasMany(SurveyModel::class, 'survey_category_id', 'id');
    }

    public function teacher (): BelongsTo
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
    }
}

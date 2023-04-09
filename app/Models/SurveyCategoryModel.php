<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyCategoryModel extends Model
{
    use HasFactory;

    protected $table = 'survey_categories';

    protected $fillable = [
        'text',
        'participants_count',
        'is_active'
    ];

    public function surveys (): HasMany
    {
        return $this->hasMany(SurveyModel::class, 'survey_category_id', 'id');
    }
}

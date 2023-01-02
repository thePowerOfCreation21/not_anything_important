<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyOptionModel extends Model
{
    use HasFactory;

    protected $table = 'survey_options';

    protected $fillable = [
        'survey_id',
        'text'
    ];

    public function survey (): BelongsTo
    {
        return $this->belongsTo(SurveyModel::class, 'survey_id', 'id');
    }
}

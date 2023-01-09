<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassMessageStudentModel extends Model
{
    use HasFactory;

    protected $table = 'class_message_student';

    protected $fillable = [
        'student_id',
        'class_message_id',
        'is_seen'
    ];

    protected $casts = [
        'is_seen' => 'boolean'
    ];

    public $timestamps = false;

    public function classMessage (): BelongsTo
    {
        return $this->belongsTo(ClassMessageModel::class, 'class_message_id', 'id');
    }

    /**
     * call this function to seen message
     *
     * @return $this
     */
    public function seen(): static
    {
        if (!$this->is_seen)
        {
            $this->is_seen = true;
            $this->save();
        }
        return $this;
    }
}

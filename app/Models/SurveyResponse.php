<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'completion_code',
        'demographic_data',
        'completed_at',
        'ip_address',
        'user_agent',
        'is_winner',
    ];

    protected $casts = [
        'demographic_data' => 'array',
        'completed_at' => 'datetime',
        'is_winner' => 'boolean',
    ];

    public function survey(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResponseAnswer::class);
    }

    public static function generateUniqueCompletionCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (static::where('completion_code', $code)->exists());

        return $code;
    }
}

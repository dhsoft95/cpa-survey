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
        'total_score',
    ];

    protected $casts = [
        'demographic_data' => 'array',
        'completed_at' => 'datetime',
        'is_winner' => 'boolean',
        'total_score' => 'integer', // Cast total score to integer
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

    /**
     * Calculate and update the total score for this response
     *
     * @return int The calculated total score
     */
    public function calculateScore(): int
    {
        $totalScore = 0;

        foreach ($this->answers as $answer) {
            // Get associated question
            $question = $answer->question;

            // Skip questions without scoring
            if (!$question) {
                continue;
            }

            // Handle scoring based on question type
            $questionType = $question->questionType->slug ?? null;

            // For multiple choice questions with single answer
            if (in_array($questionType, ['multiple-choice', 'dropdown', 'likert-scale'])) {
                // If there's a selected option
                if (!empty($answer->selected_options) && is_array($answer->selected_options)) {
                    $optionId = $answer->selected_options[0] ?? null;

                    if ($optionId) {
                        // Get option score
                        $option = QuestionOption::find($optionId);
                        if ($option && is_numeric($option->score)) {
                            $totalScore += $option->score;
                        }
                    }
                }
            }
            // For checkbox questions with multiple answers
            elseif ($questionType === 'checkbox') {
                if (!empty($answer->selected_options) && is_array($answer->selected_options)) {
                    foreach ($answer->selected_options as $optionId) {
                        $option = QuestionOption::find($optionId);
                        if ($option && is_numeric($option->score)) {
                            $totalScore += $option->score;
                        }
                    }
                }
            }
            // Text-based questions can also have scores set in settings
            elseif (in_array($questionType, ['text', 'textarea'])) {
                // Example: check if answer matches a specific expected answer
                $settings = $question->settings ?? [];

                if (isset($settings['correct_answer']) && isset($settings['score'])) {
                    if ($answer->answer_text === $settings['correct_answer']) {
                        $totalScore += (int)$settings['score'];
                    }
                }
            }
        }

        // Update the total score in the database
        $this->update(['total_score' => $totalScore]);

        return $totalScore;
    }

    /**
     * Select top winners for a specific survey
     *
     * @param int $surveyId The survey ID
     * @param int $winnerCount Number of winners to select
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function selectTopWinners(int $surveyId, int $winnerCount = 3)
    {
        // First, ensure all responses have scores calculated
        $responses = self::where('survey_id', $surveyId)
            ->whereNotNull('completed_at')
            ->get();

        foreach ($responses as $response) {
            $response->calculateScore();
        }

        // Get top scorers
        $winners = self::where('survey_id', $surveyId)
            ->whereNotNull('completed_at')
            ->orderByDesc('total_score')
            ->limit($winnerCount)
            ->get();

        // Mark these as winners
        foreach ($winners as $winner) {
            $winner->update(['is_winner' => true]);
        }

        // Unmark any previous winners that are not in this set
        self::where('survey_id', $surveyId)
            ->where('is_winner', true)
            ->whereNotIn('id', $winners->pluck('id'))
            ->update(['is_winner' => false]);

        return $winners;
    }
}

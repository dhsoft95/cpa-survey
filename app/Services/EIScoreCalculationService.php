<?php

namespace App\Services;

use App\Models\Question;
use App\Models\SurveyResponse;
use App\Models\ResponseAnswer;

class EIScoreCalculationService
{
    protected $categories = [
        'emotional self-awareness',
        'emotional expression',
        'emotional awareness of others',
        'emotional reasoning',
        'emotional self-management',
        'emotional management of others',
        'emotional self-control'
    ];

    /**
     * Calculate emotional intelligence scores for a survey response
     *
     * @param SurveyResponse $response
     * @return array
     */
    public function calculateScores(SurveyResponse $response)
    {
        $answers = $response->answers()->with('question')->get();

        // Initialize scores for each category
        $scores = [];
        foreach ($this->categories as $category) {
            $scores[str_replace(' ', '_', $category)] = [
                'score' => 0,
                'max_score' => 0,
                'count' => 0,
                'percentage' => 0
            ];
        }

        // Add a total score
        $scores['total_ei'] = [
            'score' => 0,
            'max_score' => 0,
            'count' => 0,
            'percentage' => 0
        ];

        // Calculate scores for each answer
        foreach ($answers as $answer) {
            $question = $answer->question;
            $settings = $question->settings ?? null;

            // Skip if not an EI question or doesn't have settings
            if (!$settings || !isset($settings['category'])) {
                continue;
            }

            $category = $settings['category'];
            $categoryKey = str_replace(' ', '_', $category);
            $isReverse = $settings['reverse'] ?? false;

            // Get selected option value
            $selectedOptions = $answer->selected_options;
            if (!empty($selectedOptions)) {
                $optionId = $selectedOptions[0]; // Take the first selected option for Likert
                $option = $question->options()->where('id', $optionId)->first();

                if ($option) {
                    $score = $option->score;

                    // Reverse score if needed
                    if ($isReverse) {
                        $score = 6 - $score; // Reverse (1=5, 2=4, 3=3, 4=2, 5=1)
                    }

                    // Add to category score
                    $scores[$categoryKey]['score'] += $score;
                    $scores[$categoryKey]['max_score'] += 5; // Max possible score is 5
                    $scores[$categoryKey]['count']++;

                    // Add to total score
                    $scores['total_ei']['score'] += $score;
                    $scores['total_ei']['max_score'] += 5;
                    $scores['total_ei']['count']++;
                }
            }
        }

        // Calculate percentages
        foreach ($scores as $category => &$data) {
            if ($data['max_score'] > 0) {
                $data['percentage'] = round(($data['score'] / $data['max_score']) * 100, 1);
                $data['average'] = round($data['score'] / ($data['count'] > 0 ? $data['count'] : 1), 1);
            }
        }

        return $scores;
    }

    /**
     * Generate a text interpretation of EI scores
     *
     * @param array $scores
     * @return array
     */
    public function generateInterpretation(array $scores)
    {
        $interpretations = [];

        // Define score ranges and interpretations
        $ranges = [
            ['min' => 0, 'max' => 20, 'level' => 'Very Low', 'description' => 'This area needs significant improvement.'],
            ['min' => 20, 'max' => 40, 'level' => 'Low', 'description' => 'This is an area where you could focus development efforts.'],
            ['min' => 40, 'max' => 60, 'level' => 'Average', 'description' => 'You demonstrate this competency at an average level.'],
            ['min' => 60, 'max' => 80, 'level' => 'High', 'description' => 'This is a strength area for you.'],
            ['min' => 80, 'max' => 100, 'level' => 'Very High', 'description' => 'This is a significant strength that you can leverage.']
        ];

        // Category specific descriptions
        $categoryDescriptions = [
            'emotional_self_awareness' => [
                'title' => 'Emotional Self-Awareness',
                'description' => 'Your ability to perceive and understand your own emotions.',
                'improvement' => 'Try practicing mindfulness or journaling to increase awareness of your emotional states.'
            ],
            'emotional_expression' => [
                'title' => 'Emotional Expression',
                'description' => 'How effectively you express your emotions.',
                'improvement' => 'Practice articulating your feelings constructively, especially in challenging situations.'
            ],
            'emotional_awareness_of_others' => [
                'title' => 'Emotional Awareness of Others',
                'description' => 'Your ability to perceive and understand others\' emotions.',
                'improvement' => 'Focus on active listening and observing non-verbal cues when interacting with colleagues.'
            ],
            'emotional_reasoning' => [
                'title' => 'Emotional Reasoning',
                'description' => 'How well you use emotional information in decision-making.',
                'improvement' => 'Consider both logical and emotional impacts when making important decisions.'
            ],
            'emotional_self_management' => [
                'title' => 'Emotional Self-Management',
                'description' => 'Your ability to manage your own emotions effectively.',
                'improvement' => 'Develop strategies for maintaining positivity and managing stress at work.'
            ],
            'emotional_management_of_others' => [
                'title' => 'Emotional Management of Others',
                'description' => 'How well you influence the emotions of others.',
                'improvement' => 'Focus on creating a positive environment and helping colleagues manage difficult emotions.'
            ],
            'emotional_self_control' => [
                'title' => 'Emotional Self-Control',
                'description' => 'Your ability to control intense emotions appropriately.',
                'improvement' => 'Practice techniques like deep breathing to help maintain composure in challenging situations.'
            ],
            'total_ei' => [
                'title' => 'Overall Emotional Intelligence',
                'description' => 'Your overall emotional intelligence score across all dimensions.',
                'improvement' => 'Consider your lowest scoring areas as opportunities for focused development.'
            ]
        ];

        // Generate interpretation for each category
        foreach ($scores as $category => $data) {
            if ($category == 'total_ei' || isset($categoryDescriptions[$category])) {
                $percentage = $data['percentage'];

                // Find applicable range
                $level = 'Undetermined';
                $rangeDescription = '';

                foreach ($ranges as $range) {
                    if ($percentage > $range['min'] && $percentage <= $range['max']) {
                        $level = $range['level'];
                        $rangeDescription = $range['description'];
                        break;
                    }
                }

                // Build interpretation
                $interpretation = [
                    'title' => $categoryDescriptions[$category]['title'],
                    'score' => $data['average'],
                    'percentage' => $percentage,
                    'level' => $level,
                    'description' => $categoryDescriptions[$category]['description'],
                    'evaluation' => $rangeDescription,
                    'improvement' => $level === 'Very High' || $level === 'High'
                        ? 'Continue to leverage this as a strength.'
                        : $categoryDescriptions[$category]['improvement']
                ];

                $interpretations[$category] = $interpretation;
            }
        }

        return $interpretations;
    }
}

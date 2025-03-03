<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyAnalyticsController extends Controller
{
    public function index(Survey $survey)
    {
        // Get completed responses with scores
        $responses = SurveyResponse::where('survey_id', $survey->id)
            ->whereNotNull('completed_at')
            ->whereNotNull('total_score')
            ->get();

        // EI categories
        $categories = [
            'esa' => 'Emotional Self-Awareness',
            'ee' => 'Emotional Expression',
            'eao' => 'Emotional Awareness of Others',
            'er' => 'Emotional Reasoning',
            'esm' => 'Emotional Self-Management',
            'emo' => 'Emotional Management of Others',
            'esc' => 'Emotional Self-Control',
        ];

        // Calculate statistics for each category
        $categoryStats = [];
        $totalScoreData = [];

        foreach ($categories as $code => $name) {
            $scores = $responses->map(function ($response) use ($code) {
                return $response->demographic_data['ei_scores'][$code]['raw'] ?? 0;
            })->filter();

            if ($scores->isEmpty()) {
                $categoryStats[$code] = [
                    'name' => $name,
                    'count' => 0,
                    'min' => 0,
                    'max' => 0,
                    'avg' => 0,
                    'median' => 0,
                    'std_dev' => 0,
                    'distribution' => [],
                ];
                continue;
            }

            // Get all raw scores for distribution chart
            $allScores = $responses->pluck("demographic_data.ei_scores.{$code}.raw")->filter();

            // Calculate distribution for histogram
            $distribution = [];
            if ($allScores->count() > 0) {
                $min = max(0, $allScores->min());
                $max = $allScores->max();
                $range = max(1, $max - $min);
                $bucketSize = ceil($range / 10); // Create around 10 buckets

                // Initialize buckets
                $buckets = [];
                for ($i = $min; $i <= $max; $i += $bucketSize) {
                    $buckets[$i] = 0;
                }

                // Fill buckets
                foreach ($allScores as $score) {
                    $bucket = floor(($score - $min) / $bucketSize) * $bucketSize + $min;
                    $buckets[$bucket] = ($buckets[$bucket] ?? 0) + 1;
                }

                // Format for chart
                foreach ($buckets as $bucket => $count) {
                    $distribution[] = [
                        'range' => $bucket . '-' . min($max, $bucket + $bucketSize - 1),
                        'count' => $count,
                    ];
                }
            }

            // Calculate statistics
            $count = $scores->count();
            $min = $scores->min();
            $max = $scores->max();
            $avg = $scores->avg();

            // Calculate median
            $sortedScores = $scores->sort()->values();
            $middle = floor(($count - 1) / 2);
            $median = $count > 0 ? $sortedScores[$middle] : 0;
            if ($count % 2 === 0 && $count > 0) {
                $median = ($median + $sortedScores[$middle + 1]) / 2;
            }

            // Calculate standard deviation
            $variance = 0;
            foreach ($scores as $score) {
                $variance += pow($score - $avg, 2);
            }
            $stdDev = $count > 0 ? sqrt($variance / $count) : 0;

            $categoryStats[$code] = [
                'name' => $name,
                'count' => $count,
                'min' => $min,
                'max' => $max,
                'avg' => round($avg, 2),
                'median' => round($median, 2),
                'std_dev' => round($stdDev, 2),
                'distribution' => $distribution,
            ];
        }

        // Calculate total score statistics
        $totalScores = $responses->pluck('total_score')->filter();
        if ($totalScores->count() > 0) {
            // Calculate distribution for histogram
            $min = max(0, $totalScores->min());
            $max = $totalScores->max();
            $range = max(1, $max - $min);
            $bucketSize = ceil($range / 10); // Create around 10 buckets

            // Initialize buckets
            $distribution = [];
            $buckets = [];
            for ($i = $min; $i <= $max; $i += $bucketSize) {
                $buckets[$i] = 0;
            }

            // Fill buckets
            foreach ($totalScores as $score) {
                $bucket = floor(($score - $min) / $bucketSize) * $bucketSize + $min;
                $buckets[$bucket] = ($buckets[$bucket] ?? 0) + 1;
            }

            // Format for chart
            foreach ($buckets as $bucket => $count) {
                $distribution[] = [
                    'range' => $bucket . '-' . min($max, $bucket + $bucketSize - 1),
                    'count' => $count,
                ];
            }

            $totalScoreData = [
                'count' => $totalScores->count(),
                'min' => $totalScores->min(),
                'max' => $totalScores->max(),
                'avg' => round($totalScores->avg(), 2),
                'distribution' => $distribution,
            ];
        } else {
            $totalScoreData = [
                'count' => 0,
                'min' => 0,
                'max' => 0,
                'avg' => 0,
                'distribution' => [],
            ];
        }

        // Get demographic breakdowns
        $genderBreakdown = $this->getBreakdownByField($responses, 'gender');
        $ageBreakdown = $this->getBreakdownByAgeGroup($responses);
        $industryBreakdown = $this->getBreakdownByField($responses, 'industry');
        $jobTitleBreakdown = $this->getBreakdownByField($responses, 'job_title');
        $yearsDesignationBreakdown = $this->getBreakdownByYearsGroup($responses);

        return view('surveys.analytics', compact(
            'survey',
            'responses',
            'categories',
            'categoryStats',
            'totalScoreData',
            'genderBreakdown',
            'ageBreakdown',
            'industryBreakdown',
            'jobTitleBreakdown',
            'yearsDesignationBreakdown'
        ));
    }

    /**
     * Get breakdown by demographic field
     */
    private function getBreakdownByField($responses, $field)
    {
        $breakdown = [];
        $validResponses = $responses->filter(function ($response) use ($field) {
            return isset($response->demographic_data[$field]) && !empty($response->demographic_data[$field]);
        });

        if ($validResponses->isEmpty()) {
            return [];
        }

        // Count occurrences
        $counts = [];
        foreach ($validResponses as $response) {
            $value = $response->demographic_data[$field];
            $counts[$value] = ($counts[$value] ?? 0) + 1;
        }

        // Calculate averages
        foreach ($counts as $value => $count) {
            $filteredResponses = $validResponses->filter(function ($response) use ($field, $value) {
                return $response->demographic_data[$field] == $value;
            });

            $totalScore = $filteredResponses->sum('total_score');
            $avgScore = $count > 0 ? round($totalScore / $count, 2) : 0;

            $breakdown[] = [
                'category' => $value,
                'count' => $count,
                'percentage' => round($count / $validResponses->count() * 100, 1),
                'avg_score' => $avgScore,
            ];
        }

        return $breakdown;
    }

    /**
     * Get breakdown by age groups
     */
    private function getBreakdownByAgeGroup($responses)
    {
        $currentYear = date('Y');
        $ageGroups = [
            '18-25' => [0, 25],
            '26-35' => [26, 35],
            '36-45' => [36, 45],
            '46-55' => [46, 55],
            '56-65' => [56, 65],
            '66+' => [66, 120],
        ];

        $breakdown = [];
        $validResponses = $responses->filter(function ($response) {
            return isset($response->demographic_data['birth_year']) &&
                is_numeric($response->demographic_data['birth_year']);
        });

        if ($validResponses->isEmpty()) {
            return [];
        }

        // Initialize groups
        $counts = [];
        foreach ($ageGroups as $group => $range) {
            $counts[$group] = 0;
        }

        // Count occurrences
        foreach ($validResponses as $response) {
            $birthYear = (int) $response->demographic_data['birth_year'];
            $age = $currentYear - $birthYear;

            foreach ($ageGroups as $group => $range) {
                if ($age >= $range[0] && $age <= $range[1]) {
                    $counts[$group] = ($counts[$group] ?? 0) + 1;
                    break;
                }
            }
        }

        // Calculate averages
        foreach ($ageGroups as $group => $range) {
            $count = $counts[$group] ?? 0;
            if ($count === 0) continue;

            $filteredResponses = $validResponses->filter(function ($response) use ($range, $currentYear) {
                $birthYear = (int) $response->demographic_data['birth_year'];
                $age = $currentYear - $birthYear;
                return $age >= $range[0] && $age <= $range[1];
            });

            $totalScore = $filteredResponses->sum('total_score');
            $avgScore = $count > 0 ? round($totalScore / $count, 2) : 0;

            $breakdown[] = [
                'category' => $group,
                'count' => $count,
                'percentage' => round($count / $validResponses->count() * 100, 1),
                'avg_score' => $avgScore,
            ];
        }

        return $breakdown;
    }

    /**
     * Get breakdown by years since designation
     */
    private function getBreakdownByYearsGroup($responses)
    {
        $yearsGroups = [
            '0-5 years' => [0, 5],
            '6-10 years' => [6, 10],
            '11-15 years' => [11, 15],
            '16-20 years' => [16, 20],
            '21+ years' => [21, 100],
        ];

        $breakdown = [];
        $validResponses = $responses->filter(function ($response) {
            return isset($response->demographic_data['years_designation']) &&
                is_numeric($response->demographic_data['years_designation']);
        });

        if ($validResponses->isEmpty()) {
            return [];
        }

        // Initialize groups
        $counts = [];
        foreach ($yearsGroups as $group => $range) {
            $counts[$group] = 0;
        }

        // Count occurrences
        foreach ($validResponses as $response) {
            $years = (int) $response->demographic_data['years_designation'];

            foreach ($yearsGroups as $group => $range) {
                if ($years >= $range[0] && $years <= $range[1]) {
                    $counts[$group] = ($counts[$group] ?? 0) + 1;
                    break;
                }
            }
        }

        // Calculate averages
        foreach ($yearsGroups as $group => $range) {
            $count = $counts[$group] ?? 0;
            if ($count === 0) continue;

            $filteredResponses = $validResponses->filter(function ($response) use ($range) {
                $years = (int) $response->demographic_data['years_designation'];
                return $years >= $range[0] && $years <= $range[1];
            });

            $totalScore = $filteredResponses->sum('total_score');
            $avgScore = $count > 0 ? round($totalScore / $count, 2) : 0;

            $breakdown[] = [
                'category' => $group,
                'count' => $count,
                'percentage' => round($count / $validResponses->count() * 100, 1),
                'avg_score' => $avgScore,
            ];
        }

        return $breakdown;
    }
}

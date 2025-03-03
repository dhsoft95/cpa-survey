<?php

namespace App\Exports;

use App\Models\SurveyResponse;
use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable; // Add this trait
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyResponsesExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize, WithMultipleSheets
{
    use Exportable; // Use the Exportable trait here

    protected $surveyId;
    protected $includeResponses;

    public function __construct(int $surveyId, bool $includeResponses = true)
    {
        $this->surveyId = $surveyId;
        $this->includeResponses = $includeResponses;
    }

    public function collection()
    {
        return SurveyResponse::with(['answers', 'answers.question'])
            ->where('survey_id', $this->surveyId)
            ->whereNotNull('completed_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Completion Code',
            'Completed At',
            'Is Winner',
            'CPA Member',
            'Birth Year',
            'Gender',
            'Provincial Body',
            'Industry',
            'Job Title',
            'Years Since Designation',
            'Yearly Compensation',
            'Total EI Score',
            'Emotional Self-Awareness',
            'Emotional Expression',
            'Emotional Awareness of Others',
            'Emotional Reasoning',
            'Emotional Self-Management',
            'Emotional Management of Others',
            'Emotional Self-Control',
            'IP Address',
            'Created At',
        ];
    }

    public function map($response): array
    {
        return [
            $response->id,
            $response->completion_code,
            $response->completed_at ? $response->completed_at->format('Y-m-d H:i:s') : null,
            $response->is_winner ? 'Yes' : 'No',
            $response->demographic_data['is_cpa_member'] ?? 'Unknown',
            $response->demographic_data['birth_year'] ?? null,
            $response->demographic_data['gender'] ?? null,
            $response->demographic_data['provincial_cpa_body'] ?? null,
            $response->demographic_data['industry'] ?? null,
            $response->demographic_data['job_title'] ?? null,
            $response->demographic_data['years_designation'] ?? null,
            $response->demographic_data['yearly_compensation'] ?? null,
            $response->total_score ?? 0,
            $response->demographic_data['ei_scores']['esa']['raw'] ?? 0,
            $response->demographic_data['ei_scores']['ee']['raw'] ?? 0,
            $response->demographic_data['ei_scores']['eao']['raw'] ?? 0,
            $response->demographic_data['ei_scores']['er']['raw'] ?? 0,
            $response->demographic_data['ei_scores']['esm']['raw'] ?? 0,
            $response->demographic_data['ei_scores']['emo']['raw'] ?? 0,
            $response->demographic_data['ei_scores']['esc']['raw'] ?? 0,
            $response->ip_address ?? null,
            $response->created_at ? $response->created_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public function title(): string
    {
        return 'Survey Responses';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headings)
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function sheets(): array
    {
        $sheets = [
            $this, // Main responses sheet
            new SurveyScoreAnalyticsSheet($this->surveyId),
        ];

        if ($this->includeResponses) {
            $sheets[] = new SurveyAnswersExport($this->surveyId);
        }

        return $sheets;
    }
}

/**
 * Sheet for detailed breakdown of survey answers
 */
class SurveyAnswersExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    use Exportable; // Add Exportable trait here too

    protected $surveyId;

    public function __construct(int $surveyId)
    {
        $this->surveyId = $surveyId;
    }

    public function collection()
    {
        // Get all answers for this survey
        return \App\Models\ResponseAnswer::whereHas('surveyResponse', function ($query) {
            $query->where('survey_id', $this->surveyId)
                ->whereNotNull('completed_at');
        })
            ->with(['surveyResponse', 'question', 'question.questionType'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Response ID',
            'Completion Code',
            'Question ID',
            'Question',
            'Answer Type',
            'Selected Option ID',
            'Selected Option Text',
            'Text Answer',
            'Score',
        ];
    }

    public function map($answer): array
    {
        // Check if we need to get the selected option info
        $optionId = null;
        $optionText = null;
        $score = null;

        if (!empty($answer->selected_options) && is_array($answer->selected_options)) {
            $optionId = $answer->selected_options[0] ?? null;

            if ($optionId) {
                $option = \App\Models\QuestionOption::find($optionId);
                if ($option) {
                    $optionText = $option->option_text;
                    $score = $option->score;
                }
            }
        }

        return [
            $answer->surveyResponse->id ?? null,
            $answer->surveyResponse->completion_code ?? null,
            $answer->question_id,
            $answer->question->question_text ?? 'Unknown Question',
            $answer->question->questionType->name ?? 'Unknown Type',
            $optionId,
            $optionText,
            $answer->answer_text,
            $score,
        ];
    }

    public function title(): string
    {
        return 'Detailed Answers';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

/**
 * Sheet for score analytics
 */
class SurveyScoreAnalyticsSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    use Exportable; // Add Exportable trait here too

    protected $surveyId;

    public function __construct(int $surveyId)
    {
        $this->surveyId = $surveyId;
    }

    public function collection()
    {
        $survey = Survey::findOrFail($this->surveyId);
        $responses = SurveyResponse::where('survey_id', $this->surveyId)
            ->whereNotNull('completed_at')
            ->whereNotNull('total_score')
            ->get();

        // Calculate statistics for each category
        $categories = [
            'esa' => 'Emotional Self-Awareness',
            'ee' => 'Emotional Expression',
            'eao' => 'Emotional Awareness of Others',
            'er' => 'Emotional Reasoning',
            'esm' => 'Emotional Self-Management',
            'emo' => 'Emotional Management of Others',
            'esc' => 'Emotional Self-Control',
            'total' => 'Total Score',
        ];

        $stats = [];
        foreach ($categories as $code => $name) {
            $scores = $responses->map(function ($response) use ($code) {
                if ($code === 'total') {
                    return $response->total_score ?? 0;
                }

                return $response->demographic_data['ei_scores'][$code]['raw'] ?? 0;
            })->filter();

            if ($scores->isEmpty()) {
                $stats[] = [
                    'category' => $name,
                    'code' => $code,
                    'count' => 0,
                    'min' => 0,
                    'max' => 0,
                    'avg' => 0,
                    'median' => 0,
                    'std_dev' => 0,
                ];
                continue;
            }

            // Calculate statistics
            $count = $scores->count();
            $min = $scores->min();
            $max = $scores->max();
            $avg = $scores->avg();

            // Calculate median
            $sortedScores = $scores->sort()->values();
            $middle = floor(($count - 1) / 2);
            $median = $sortedScores[$middle];
            if ($count % 2 === 0) {
                $median = ($median + $sortedScores[$middle + 1]) / 2;
            }

            // Calculate standard deviation
            $variance = 0;
            foreach ($scores as $score) {
                $variance += pow($score - $avg, 2);
            }
            $stdDev = $count > 0 ? sqrt($variance / $count) : 0;

            $stats[] = [
                'category' => $name,
                'code' => $code,
                'count' => $count,
                'min' => $min,
                'max' => $max,
                'avg' => round($avg, 2),
                'median' => round($median, 2),
                'std_dev' => round($stdDev, 2),
            ];
        }

        return collect($stats);
    }

    public function headings(): array
    {
        return [
            'Category',
            'Code',
            'Response Count',
            'Minimum',
            'Maximum',
            'Average',
            'Median',
            'Standard Deviation',
        ];
    }

    public function title(): string
    {
        return 'Score Analytics';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

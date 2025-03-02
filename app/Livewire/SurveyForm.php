<?php
namespace App\Livewire;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\ResponseAnswer;
use App\Models\QuestionOption;
use Livewire\Component;

class SurveyForm extends Component
{
    public Survey $survey;
    public $currentStep = 1;
    public $totalSteps = 3;
    public $demographicQuestions = [];
    public $careerSatisfactionQuestions = [];
    public $answers = [];
    public $demographicData = [
        'is_cpa_member' => null,
        'birth_year' => null,
        'gender' => null,
        'languages' => [],
        'legacy_designation' => [],
        'years_designation' => null,
        'province' => null,
        'industry' => null,
        'provincial_cpa_body' => null,
        'current_position' => null,
        'number_staff' => null,
        'work_nature' => null,
        'yearly_compensation' => null,
        'job_title' => null,
        'number_overseen' => null,
    ];

    public $completionCode = null;
    public $consentAgreed = false;
    public $careerChallengesText = null;

    // New properties for score display
    public $showScores = false;
    public $scores = [];
    public $totalScore = 0;
    public $eiScores = [];

    // EI Category definitions for scoring
    private $eiCategories = [
        'self_awareness' => [1, 5, 9, 13, 17], // Question indices that measure self-awareness
        'self_regulation' => [2, 6, 10, 14, 18], // Question indices for self-regulation
        'motivation' => [3, 7, 11, 15, 19], // Questions for motivation
        'empathy' => [4, 8, 12, 16, 20], // Questions for empathy
        'social_skills' => [21, 22, 23, 24, 25] // Questions for social skills
    ];

    public function mount(Survey $survey)
    {
        $this->survey = $survey;
        $allQuestions = $survey->questions()->with(['questionType', 'options'])->orderBy('order')->get();

        foreach ($allQuestions as $question) {
            if (strpos($question->question_text, 'section below is based on') !== false) {
                continue;
            }

            if (count($this->demographicQuestions) < 15 &&
                !str_contains(strtolower($question->question_text), 'satisfied')) {
                $this->demographicQuestions[] = $question->toArray();
            } else {
                $this->careerSatisfactionQuestions[] = $question->toArray();
            }

            if ($question->questionType && in_array($question->questionType->slug, ['multiple-choice', 'dropdown', 'likert-scale'])) {
                $this->answers[$question->id] = ['selected_option' => null];
            } else {
                $this->answers[$question->id] = ['answer_text' => null];
            }
        }
    }

    public function nextStep()
    {
        // Step 1: Consent form
        if ($this->currentStep == 1) {
            $this->validate([
                'consentAgreed' => 'accepted',
            ]);
            $this->currentStep = 2;
        }
        // Step 2: CPA member check and demographics
        elseif ($this->currentStep == 2) {
            $this->validate([
                'demographicData.is_cpa_member' => 'required',
            ]);

            // End survey immediately if not a CPA member
            if ($this->demographicData['is_cpa_member'] === 'no') {
                $this->submitSurvey(false);
                return;
            }

            // For CPA members, validate demographic information
            try {
                $this->validate([
                    'demographicData.birth_year' => 'required|numeric|min:1900|max:' . date('Y'),
                    'demographicData.gender' => 'required',
                    'demographicData.languages' => 'required|array|min:1',
                    'demographicData.legacy_designation' => 'required|array|min:1',
                    'demographicData.years_designation' => 'required|numeric|min:0',
                    'demographicData.industry' => 'required',
                    'demographicData.provincial_cpa_body' => 'required|string',
                    'demographicData.current_position' => 'required|string',
                    'demographicData.number_staff' => 'required|numeric|min:0',
                    'demographicData.work_nature' => 'required|string',
                    'demographicData.yearly_compensation' => 'required|numeric|min:0',
                    'demographicData.job_title' => 'required|string',
                    'demographicData.number_overseen' => 'required|numeric|min:0',
                ]);

                $this->currentStep = 3;
            } catch (\Illuminate\Validation\ValidationException $e) {
                throw $e;
            }
        }
        // Step 3: Career satisfaction questions
        elseif ($this->currentStep == 3) {
            $rules = [];
            foreach ($this->careerSatisfactionQuestions as $question) {
                if ($question['question_type']['slug'] === 'likert-scale') {
                    $rules["answers.{$question['id']}.selected_option"] = 'required';
                }
            }

            if (!empty($rules)) {
                $this->validate($rules, [
                    'answers.*.selected_option.required' => 'Please select an option for this question.'
                ]);
            }

            $this->submitSurvey(true);
        }
    }

    public function previousStep()
    {
        $this->currentStep = max(1, $this->currentStep - 1);
    }

    public function submitSurvey($includeAllQuestions = true)
    {
        $response = SurveyResponse::create([
            'survey_id' => $this->survey->id,
            'completion_code' => SurveyResponse::generateUniqueCompletionCode(),
            'demographic_data' => $this->demographicData,
            'completed_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // If not a CPA member, we only save the CPA membership status
        if (!$includeAllQuestions) {
            $this->completionCode = $response->completion_code;
            return;
        }

        // For CPA members, save all answers and calculate scores
        $scoreData = [];

        foreach ($this->answers as $questionId => $answer) {
            // Skip any non-numeric keys
            if (!is_numeric($questionId)) {
                continue;
            }

            // For regular questions
            ResponseAnswer::create([
                'survey_response_id' => $response->id,
                'question_id' => $questionId,
                'selected_options' => isset($answer['selected_option']) && $answer['selected_option'] ? [$answer['selected_option']] : [],
                'answer_text' => $answer['answer_text'] ?? null,
            ]);

            // Collect score data for Likert scale questions
            if (isset($answer['selected_option']) && $answer['selected_option']) {
                $option = QuestionOption::find($answer['selected_option']);
                if ($option && isset($option->score)) {
                    $scoreData[$questionId] = $option->score;
                }
            }
        }

        // Store the career challenges text in demographic data
        if (!empty($this->careerChallengesText)) {
            $demographicData = $response->demographic_data;
            $demographicData['career_challenges'] = $this->careerChallengesText;
            $response->update(['demographic_data' => $demographicData]);
        }

        // Calculate and store scores
        $this->calculateScores($scoreData);

        // Calculate the total score
        $totalScore = $response->calculateScore();

        // Store scores in response
        $demographicData = $response->demographic_data;
        $demographicData['ei_scores'] = $this->eiScores;
        $demographicData['total_score'] = $totalScore;
        $response->update(['demographic_data' => $demographicData]);

        // Set completion code and show scores
        $this->completionCode = $response->completion_code;
        $this->totalScore = $totalScore;
        $this->showScores = true;
    }

    /**
     * Calculate EI scores for different categories
     *
     * @param array $scoreData
     */
    private function calculateScores($scoreData)
    {
        // Initialize category scores
        $this->eiScores = [
            'self_awareness' => 0,
            'self_regulation' => 0,
            'motivation' => 0,
            'empathy' => 0,
            'social_skills' => 0
        ];

        // Map questions to categories and calculate scores
        foreach ($this->careerSatisfactionQuestions as $index => $question) {
            $questionId = $question['id'];
            if (isset($scoreData[$questionId])) {
                $score = $scoreData[$questionId];

                // Determine which category this question belongs to
                foreach ($this->eiCategories as $category => $questionIndices) {
                    if (in_array($index + 1, $questionIndices)) {
                        $this->eiScores[$category] += $score;
                        break;
                    }
                }
            }
        }

        // Calculate score percentages (assuming 5 questions per category, max score of 5 per question)
        foreach ($this->eiScores as $category => $score) {
            $maxPossible = count($this->eiCategories[$category]) * 5;
            $this->eiScores[$category] = [
                'raw' => $score,
                'percentage' => round(($score / $maxPossible) * 100)
            ];
        }
    }

    public function render()
    {
        return view('livewire.survey-form');
    }
}

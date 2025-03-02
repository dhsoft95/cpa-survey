<?php
namespace App\Livewire;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\ResponseAnswer;
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
        'legacy_designation' => [], // Changed to array for multiple selection
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

    public $validationErrors = [];

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
                $this->submitSurvey(false); // Pass false to indicate non-CPA member
                return;
            }

            // For CPA members, validate demographic information
            try {
                $this->validate([
                    'demographicData.birth_year' => 'required|numeric|min:1900|max:' . date('Y'),
                    'demographicData.gender' => 'required',
                    'demographicData.languages' => 'required|array|min:1',
                    'demographicData.legacy_designation' => 'required|array|min:1', // Updated validation for array
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

                // If validation passes, proceed to step 3
                $this->currentStep = 3;
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Store the validation errors for debugging
                $this->validationErrors = $e->errors();
                throw $e;
            }
        }
        // Step 3: Career satisfaction questions
        elseif ($this->currentStep == 3) {
            // Only validate required questions
            $rules = [];
            foreach ($this->careerSatisfactionQuestions as $question) {
                if ($question['is_required']) {
                    $rules["answers.{$question['id']}.selected_option"] = 'required';
                }
            }

            if (!empty($rules)) {
                $this->validate($rules);
            }

            $this->submitSurvey(true); // Pass true to indicate CPA member
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
            // We don't need to save any answers for non-CPA members
            // Just set the completion code and return
            $this->completionCode = $response->completion_code;
            return;
        }

        // For CPA members, save all answers
        foreach ($this->answers as $questionId => $answer) {
            // Skip any non-numeric keys (safety check)
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
        }

        // Store the career challenges text in the demographic data
        if (!empty($this->careerChallengesText)) {
            $demographicData = $response->demographic_data;
            $demographicData['career_challenges'] = $this->careerChallengesText;
            $response->update(['demographic_data' => $demographicData]);
        }

        $this->completionCode = $response->completion_code;
    }

    public function render()
    {
        return view('livewire.survey-form');
    }
}

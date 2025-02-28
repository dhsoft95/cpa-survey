<?php
// app/Livewire/SurveyForm.php
namespace App\Livewire;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\ResponseAnswer;
use Livewire\Component;

class SurveyForm extends Component
{
    public Survey $survey;
    public $currentStep = 1;
    public $totalSteps = 2; // Fixed to 2 steps: Demographics and Career Satisfaction
    public $demographicQuestions = [];
    public $careerSatisfactionQuestions = [];
    public $answers = [];
    public $demographicData = [
        'is_cpa_member' => null, // Yes/No for CPA Canada membership
        'birth_year' => null,
        'gender' => null,
        'languages' => [],
        'legacy_designation' => null,
        'years_designation' => null,
        'province' => null,
        'industry' => null,
    ];

    public $completionCode = null;
    public $consentAgreed = false;

    public function mount(Survey $survey)
    {
        $this->survey = $survey;

        // Get all questions with their types and options
        $allQuestions = $survey->questions()->with(['questionType', 'options'])->orderBy('order')->get();

        // Split questions based on content (simple approach - demographic questions first, then satisfaction)
        foreach ($allQuestions as $question) {
            // Skip informational text "questions" that aren't actual questions
            if (strpos($question->question_text, 'section below is based on') !== false) {
                continue;
            }

            if (count($this->demographicQuestions) < 7 &&
                !str_contains(strtolower($question->question_text), 'satisfied')) {
                $this->demographicQuestions[] = $question->toArray();
            } else {
                $this->careerSatisfactionQuestions[] = $question->toArray();
            }

            // Initialize answer structure based on question type
            if ($question->questionType && in_array($question->questionType->slug, ['multiple-choice', 'dropdown', 'likert-scale'])) {
                // For single-select types
                $this->answers[$question->id] = [
                    'answer_text' => null,
                    'selected_option' => null,
                ];
            } else {
                // For multi-select or text-based types
                $this->answers[$question->id] = [
                    'answer_text' => null,
                    'selected_options' => [],
                ];
            }
        }
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            // Validate demographics
            $rules = [
                'demographicData.is_cpa_member' => 'required',
                'consentAgreed' => 'accepted',
            ];

            if ($this->demographicData['is_cpa_member'] === 'yes') {
                // Only validate other demographic fields if they are a CPA member
                $rules = array_merge($rules, [
                    'demographicData.birth_year' => 'required|numeric|min:1900|max:' . date('Y'),
                    'demographicData.gender' => 'required',
                    'demographicData.languages' => 'required|array|min:1',
                    'demographicData.legacy_designation' => 'required',
                    'demographicData.years_designation' => 'required|numeric|min:0',
                    'demographicData.province' => 'required',
                    'demographicData.industry' => 'required',
                ]);
            }

            $this->validate($rules);
            $this->currentStep = 2;

        } elseif ($this->currentStep == 2) {
            // Validate career satisfaction questions
            $rules = [];

            foreach ($this->careerSatisfactionQuestions as $question) {
                if ($question['is_required']) {
                    $questionId = $question['id'];
                    $questionType = $question['question_type']['slug'] ?? null;

                    if ($questionType) {
                        if (in_array($questionType, ['multiple-choice', 'dropdown', 'likert-scale'])) {
                            $rules["answers.{$questionId}.selected_option"] = 'required';
                        } else {
                            $rules["answers.{$questionId}.answer_text"] = 'required';
                        }
                    }
                }
            }

            $this->validate($rules);
            $this->submitSurvey();
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submitSurvey()
    {
        // Create survey response
        $surveyResponse = SurveyResponse::create([
            'survey_id' => $this->survey->id,
            'completion_code' => SurveyResponse::generateUniqueCompletionCode(),
            'demographic_data' => $this->demographicData,
            'completed_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Save all answers
        foreach ($this->answers as $questionId => $answerData) {
            // Convert single-select option to array for consistent storage
            $selectedOptions = isset($answerData['selected_option']) && $answerData['selected_option']
                ? [$answerData['selected_option']]
                : ($answerData['selected_options'] ?? []);

            ResponseAnswer::create([
                'survey_response_id' => $surveyResponse->id,
                'question_id' => $questionId,
                'answer_text' => $answerData['answer_text'] ?? null,
                'selected_options' => $selectedOptions,
            ]);
        }

        $this->completionCode = $surveyResponse->completion_code;
    }

    public function render()
    {
        return view('livewire.survey-form');
    }
}

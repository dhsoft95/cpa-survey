<?php
namespace App\Livewire;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\ResponseAnswer;
use App\Models\QuestionOption;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class SurveyForm extends Component
{
    public Survey $survey;
    public $currentStep = 1;
    public $totalSteps = 5; // Using 5 steps total
    public $demographicQuestions = [];
    public $careerSatisfactionQuestions = [];
    public $eiQuestions = []; // Added for EI specific questions
    public $answers = [];
    public $demographicData = [
        'is_cpa_member' => null,
        'birth_year' => null,
        'gender' => null,
        'languages' => [],
        'legacy_designation' => [],
        'years_designation' => null,
        'province' => null,
        'provincial_bodies' => [],
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
    public $showNonCpaThanks = false;

    // Score display properties
    public $showScores = false;
    public $scores = [];
    public $totalScore = 0;
    public $eiScores = [];

    // Loading state
    public $isSubmitting = false;

    // Genos EI Category definitions for scoring
    private $eiCategories = [
        'esa' => 'Emotional Self-Awareness',
        'ee' => 'Emotional Expression',
        'eao' => 'Emotional Awareness of Others',
        'er' => 'Emotional Reasoning',
        'esm' => 'Emotional Self-Management',
        'emo' => 'Emotional Management of Others',
        'esc' => 'Emotional Self-Control',
    ];

    // Question number to category mappings
    private $questionCategoryMappings = [
        'esa' => [1, 8, 15, 22, 29, 36, 43, 50, 57, 63],
        'ee' => [2, 11, 16, 23, 37, 44, 51, 58, 65, 70],
        'eao' => [3, 9, 17, 24, 31, 38, 45, 52, 59, 66],
        'er' => [4, 10, 18, 25, 32, 39, 46, 53, 60, 67],
        'esm' => [5, 19, 26, 33, 40, 47, 54, 61, 62, 69],
        'emo' => [6, 12, 13, 20, 27, 34, 41, 48, 55, 64],
        'esc' => [7, 14, 21, 28, 30, 35, 42, 49, 56, 68],
    ];

    public function mount(Survey $survey)
    {
        $this->survey = $survey;
        Log::info('Mounting SurveyForm component');

        $allQuestions = $survey->questions()->with(['questionType', 'options'])->orderBy('order')->get();
        Log::info('Retrieved ' . $allQuestions->count() . ' questions');

        foreach ($allQuestions as $question) {
            if (strpos($question->question_text, 'section below is based on') !== false) {
                continue;
            }

            $settings = $question->settings ?? [];
            $category = $settings['category'] ?? null;

            // 1. Categorize EI questions FIRST using their category
            if (in_array($category, array_keys($this->eiCategories))) {
                $this->eiQuestions[] = $question->toArray();
                Log::info('Added EI question: ' . $question->question_text);
            }
            // 2. Career satisfaction questions (text or category)
            elseif ($category === 'career_satisfaction' || str_contains(strtolower($question->question_text), 'satisfied')) {
                $this->careerSatisfactionQuestions[] = $question->toArray();
                Log::info('Added career satisfaction question: ' . $question->question_text);
            }
            // 3. All others are demographics
            else {
                $this->demographicQuestions[] = $question->toArray();
                Log::info('Added demographic question: ' . $question->question_text);
            }

            // Initialize answers...
            if ($question->questionType && in_array($question->questionType->slug, ['multiple-choice', 'dropdown', 'likert-scale'])) {
                $this->answers[$question->id] = ['selected_option' => null];
            } else {
                $this->answers[$question->id] = ['answer_text' => null];
            }
        }

        Log::info('Categorized questions: ' . count($this->demographicQuestions) . ' demographic, '
            . count($this->careerSatisfactionQuestions) . ' career satisfaction, '
            . count($this->eiQuestions) . ' EI');
    }

    public function nextStep()
    {
        // Step 1: CPA member check (was Step 2)
        if ($this->currentStep == 1) {
            $this->validate([
                'demographicData.is_cpa_member' => 'required',
            ], [
                'demographicData.is_cpa_member.required' => 'Please indicate if you are a CPA member.'
            ]);

            // End survey immediately if not a CPA member
            if ($this->demographicData['is_cpa_member'] === 'no') {
                $this->showNonCpaThanks = true;
                return;
            }

            // For CPA members, proceed to consent form
            $this->currentStep = 2;
        }
        // Step 2: Consent form (was Step 1)
        elseif ($this->currentStep == 2) {
            $this->validate([
                'consentAgreed' => 'accepted',
            ], [
                'consentAgreed.accepted' => 'You must agree to the consent form to proceed.'
            ]);
            $this->currentStep = 3;
        }
        // Step 3: Demographics
        elseif ($this->currentStep == 3) {
            // No validation required for demographics - users can skip questions
            // Proceed to Career Satisfaction questions
            $this->currentStep = 4;
        }
        // Step 4: Career Satisfaction Questions
        elseif ($this->currentStep == 4) {
            // No validation required - respondents can skip questions
            // Proceed to EI questions
            $this->currentStep = 5;
        }
        // Step 5: EI Questions
        elseif ($this->currentStep == 5) {
            // No validation required - respondents can skip questions
            $this->submitSurvey(true);
        }
        $this->js('window.scrollTo({ top: 0, behavior: "smooth" })');
    }

    public function previousStep()
    {
        $this->currentStep = max(1, $this->currentStep - 1);
        $this->js('window.scrollTo({ top: 0, behavior: "smooth" })');
    }
    public function scrollTop(): void
    {
        $this->js('window.scrollTo({ top: 0, behavior: "smooth" })');
    }

    public function submitSurvey($includeAllQuestions = true): void
    {
        $this->isSubmitting = true;

        Log::debug('Starting survey submission. includeAllQuestions=' . ($includeAllQuestions ? 'true' : 'false'));

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
            $this->isSubmitting = false;
            return;
        }

        // For CPA members, save all answers that were provided (even if incomplete)
        $scoreData = [];

        Log::debug('Number of answers to process: ' . count($this->answers));

        foreach ($this->answers as $questionId => $answer) {
            // Skip any non-numeric keys
            if (!is_numeric($questionId)) {
                continue;
            }

            Log::debug("Processing answer for question ID {$questionId}: " . json_encode($answer));

            // Only save answers that were actually provided
            if ((isset($answer['selected_option']) && $answer['selected_option']) ||
                (isset($answer['answer_text']) && !empty($answer['answer_text']))) {

                ResponseAnswer::create([
                    'survey_response_id' => $response->id,
                    'question_id' => $questionId,
                    'selected_options' => isset($answer['selected_option']) && $answer['selected_option'] ? [$answer['selected_option']] : [],
                    'answer_text' => $answer['answer_text'] ?? null,
                ]);

                // Collect score data for EI questions only
                if (isset($answer['selected_option']) && $answer['selected_option']) {
                    $optionId = $answer['selected_option'];
                    $option = QuestionOption::find($optionId);

                    Log::debug("Found option for ID {$optionId}: " . ($option ? 'yes' : 'no'));

                    if ($option) {
                        // Get the associated question to check if it's an EI question
                        $question = $option->question;

                        if ($question) {
                            $settings = $question->settings ?? [];
                            $questionNumber = $settings['question_number'] ?? null;

                            if ($questionNumber) {
                                $score = $option->score ?? null;

                                if ($score !== null) {
                                    $scoreData[$questionId] = [
                                        'score' => $score,
                                        'question_number' => $questionNumber,
                                    ];
                                    Log::debug("Added score {$score} for EI question #{$questionNumber} (ID: {$questionId})");
                                }
                            }
                        }
                    }
                }
            }
        }

        // Store the career challenges text in demographic data if provided
        if (!empty($this->careerChallengesText)) {
            $demographicData = $response->demographic_data;
            $demographicData['career_challenges'] = $this->careerChallengesText;
            $response->update(['demographic_data' => $demographicData]);
        }

        // Debug logging
        Log::debug('Score data before calculation: ' . json_encode($scoreData));
        Log::debug('Number of EI questions: ' . count($this->eiQuestions));

        // Check if we have any scores to calculate
        if (empty($scoreData)) {
            Log::warning('No score data collected from responses!');
        }

        // Calculate and store scores based on answered questions only
        $this->calculateScores($scoreData);

        // Calculate the total score
        $totalScore = 0;
        foreach ($this->eiScores as $scores) {
            $totalScore += $scores['raw'];
        }

        Log::debug('Total EI score: ' . $totalScore);

        // Store scores in response
        $demographicData = $response->demographic_data;
        $demographicData['ei_scores'] = $this->eiScores;
        $demographicData['total_ei_score'] = $totalScore;
        $response->update([
            'demographic_data' => $demographicData,
            'total_score' => $totalScore
        ]);

        // Set completion code and show scores
        $this->completionCode = $response->completion_code;
        $this->totalScore = $totalScore;
        $this->showScores = true;
        $this->isSubmitting = false;
    }

    /**
     * Calculate EI scores for different categories based on answered questions
     *
     * @param array $scoreData
     */
    private function calculateScores($scoreData)
    {
        // Initialize category scores and counts
        $this->eiScores = [];

        foreach ($this->eiCategories as $category => $categoryName) {
            $this->eiScores[$category] = [
                'name' => $categoryName,
                'raw' => 0,
                'max' => 0,
                'questions_answered' => 0
            ];
        }

        // Debug
        Log::debug('EI Score Data Count: ' . count($scoreData));
        Log::debug('EI Questions count: ' . count($this->eiQuestions));

        // Process each score from the submitted answers
        foreach ($scoreData as $questionId => $data) {
            $questionNumber = $data['question_number'];
            $score = $data['score'];

            // Find which category this question belongs to
            foreach ($this->questionCategoryMappings as $category => $questionNumbers) {
                if (in_array($questionNumber, $questionNumbers)) {
                    $this->eiScores[$category]['raw'] += $score;
                    $this->eiScores[$category]['questions_answered']++;
                    $this->eiScores[$category]['max'] += 5;

                    Log::debug("Added score {$score} to category {$category} from question #{$questionNumber}");
                    break;
                }
            }
        }

        // Debug the results
        Log::debug('Calculated EI Scores: ' . json_encode($this->eiScores));

        // Calculate if we have any questions answered in each category
        foreach ($this->eiScores as $category => &$data) {
            if ($data['questions_answered'] === 0) {
                Log::warning("No questions answered for category: {$category}");
            }
        }
    }

    public function render()
    {
        return view('livewire.survey-form');
    }
}

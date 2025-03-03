<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Survey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenosEISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Started seeding Genos EI Survey...');

        // Ensure we have the required question types
        $likertType = QuestionType::firstOrCreate(
            ['slug' => 'likert-scale'],
            ['name' => 'Likert Scale', 'description' => 'A scale from 1-5']
        );
        $multipleChoice = QuestionType::firstOrCreate(
            ['slug' => 'multiple-choice'],
            ['name' => 'Multiple Choice', 'description' => 'Select one option from multiple choices']
        );
        $dropdown = QuestionType::firstOrCreate(
            ['slug' => 'dropdown'],
            ['name' => 'Dropdown', 'description' => 'Select one option from a dropdown menu']
        );

        // Create the survey if it doesn't exist
        $survey = Survey::firstOrCreate(
            ['title' => 'EI and Career Success of CPAs'],
            [
                'description' => 'A survey to assess the relationship between emotional intelligence and career success for CPAs',
                'is_active' => true,
                'starts_at' => now(),
                'ends_at' => now()->addYear(),
            ]
        );

        $this->command->info('Survey created: ' . $survey->title);

        // Define the categories (Genos subscales)
        $categories = [
            'esa' => 'Emotional Self-Awareness',
            'ee' => 'Emotional Expression',
            'eao' => 'Emotional Awareness of Others',
            'er' => 'Emotional Reasoning',
            'esm' => 'Emotional Self-Management',
            'emo' => 'Emotional Management of Others',
            'esc' => 'Emotional Self-Control',
        ];

        // Define the question mappings to categories (based on the Genos scoring)
        $categoryMappings = [
            'esa' => [1, 8, 15, 22, 29, 36, 43, 50, 57, 63],
            'ee' => [2, 11, 16, 23, 37, 44, 51, 58, 65, 70],
            'eao' => [3, 9, 17, 24, 31, 38, 45, 52, 59, 66],
            'er' => [4, 10, 18, 25, 32, 39, 46, 53, 60, 67],
            'esm' => [5, 19, 26, 33, 40, 47, 54, 61, 62, 69],
            'emo' => [6, 12, 13, 20, 27, 34, 41, 48, 55, 64],
            'esc' => [7, 14, 21, 28, 30, 35, 42, 49, 56, 68],
        ];

        // Reverse scored items (these need to have inverted scores)
        $reverseScored = [5, 9, 11, 12, 16, 17, 21, 26, 30, 35, 38, 43, 49, 55, 57, 61, 64, 65, 67, 68];

        // Question texts from the Genos EI assessment
        $questions = [
            // Each index corresponds to the question number in the Genos assessment
            1 => 'I am aware of things that upset me at work.',
            2 => 'I effectively express how I feel about issues at work.',
            3 => 'I am aware of the things that make colleagues feel satisfied at work.',
            4 => 'I ask others how they feel about different solutions when problem solving at work.',
            5 => 'I take criticism from colleagues personally.',
            6 => 'I create a positive working environment for others.',
            7 => 'I demonstrate enthusiasm appropriately at work.',
            8 => 'I am aware of when I am feeling negative at work.',
            9 => 'I find it difficult to identify the things that motivate people at work.',
            10 => 'I demonstrate to others that I have considered their feelings in decisions I make at work.',
            11 => 'I express how I feel to the wrong people at work.',
            12 => 'I fail to get colleagues to cooperate.',
            13 => 'I motivate others toward work related goals.',
            14 => 'I remain focused when anxious about something at work.',
            15 => 'I am aware of how my feelings influence the way I respond to colleagues.',
            16 => 'I express positive emotions I experience at work inappropriately.',
            17 => 'I fail to identify the way people respond to me when building rapport.',
            18 => 'I consider the organisation\'s values when making important decisions.',
            19 => 'I engage in activities that make me feel positive at work.',
            20 => 'When necessary I effectively demonstrate empathy to colleagues.',
            21 => 'I behave inappropriately when angry at work.',
            22 => 'I am aware of my body language at work.',
            23 => 'I express how I feel at the appropriate time.',
            24 => 'I understand the things that cause others to feel engaged at work.',
            25 => 'I demonstrate to others that I have considered my own feelings when making decisions at work.',
            26 => 'I ruminate about things that anger me at work.',
            27 => 'I am effective in helping others feel positive at work.',
            28 => 'I demonstrate excitement at work appropriately.',
            29 => 'I am aware of my mood state at work.',
            30 => 'When I am under stress I become impulsive.',
            31 => 'I demonstrate an understanding of others\' feelings at work.',
            32 => 'I communicate decisions at work in a way that captures other\'s attention.',
            33 => 'I effectively deal with things that annoy me at work.',
            34 => 'I help people find effective ways of responding to upsetting events.',
            35 => 'I fail to control my temper at work.',
            36 => 'I am aware of the tone of voice I use to communicate with others at work.',
            37 => 'I provide positive feedback to colleagues.',
            38 => 'I fail to recognise when colleagues\' emotional reactions are inappropriate.',
            39 => 'I gain stakeholders\' commitment to decisions I make at work.',
            40 => 'I appropriately respond to colleagues who frustrate me at work.',
            41 => 'When colleagues are disappointed about something I help them feel differently about the situation.',
            42 => 'I hold back my initial reaction when something upsets me at work.',
            43 => 'I fail to recognise how my feelings drive my behaviour at work.',
            44 => 'When I am happy at work I express how I feel effectively.',
            45 => 'I identify others\' non verbal emotional cues (e.g., body language).',
            46 => 'I appropriately communicate decisions to stakeholders.',
            47 => 'I demonstrate positive moods and emotions at work.',
            48 => 'I help people deal with issues that cause them frustration at work.',
            49 => 'I am impatient when things don\'t get done as planned at work.',
            50 => 'I am aware of how my feelings influence the decisions I make at work.',
            51 => 'When someone upsets me at work I express how I feel effectively.',
            52 => 'I understand the things that make people feel optimistic at work.',
            53 => 'I consider the way others may react to decisions when communicating them.',
            54 => 'I quickly adjust to new conditions at work.',
            55 => 'I don\'t know what to do or say when colleagues get upset at work.',
            56 => 'When upset at work I still think clearly.',
            57 => 'I find it difficult to identify my feelings on issues at work.',
            58 => 'I effectively express optimism at work.',
            59 => 'I understand what makes people feel valued at work.',
            60 => 'I take into account both technical information and the way I feel about different choices when making decisions at work.',
            61 => 'I fail to handle stressful situations at work effectively.',
            62 => 'I respond to events that frustrate me appropriately.',
            63 => 'I am aware of things that make me feel positive at work.',
            64 => 'I fail to resolve emotional situations at work effectively.',
            65 => 'I have trouble finding the right words to express how I feel at work.',
            66 => 'I identify the way people feel about issues at work.',
            67 => 'I focus solely on facts and technical information related to problems when trying to derive a solution.',
            68 => 'I fail to keep calm in difficult situations at work.',
            69 => 'I explore the causes of things that upset me at work.',
            70 => 'When I get frustrated with something at work I discuss my frustration appropriately.',
        ];

        // Likert scale option texts
        $likertOptions = [
            1 => 'Almost Never',
            2 => 'Seldom',
            3 => 'Sometimes',
            4 => 'Usually',
            5 => 'Almost Always',
        ];

        // Create each question and its options
        DB::transaction(function () use ($survey, $likertType, $questions, $likertOptions, $reverseScored, $categories, $categoryMappings) {
            // Get existing questions for this survey to avoid duplicates
            $existingQuestions = Question::where('survey_id', $survey->id)->get();

            // Skip seeding if we already have all 70 questions
            if ($existingQuestions->count() >= 70) {
                $this->command->info('Survey already has all 70 EI questions. Skipping question creation.');
                return;
            }

            $questionOrder = $existingQuestions->count() + 1;

            foreach ($questions as $number => $text) {
                // Determine which category this question belongs to
                $category = null;
                foreach ($categoryMappings as $cat => $questionNumbers) {
                    if (in_array($number, $questionNumbers)) {
                        $category = $cat;
                        break;
                    }
                }

                // Create the question
                $isReversed = in_array($number, $reverseScored);
                $question = Question::create([
                    'survey_id' => $survey->id,
                    'question_type_id' => $likertType->id,
                    'question_text' => $text,
                    'help_text' => null,
                    'is_required' => false, // Make non-required so users can skip
                    'order' => $questionOrder++,
                    'settings' => [
                        'category' => $category,
                        'category_name' => $categories[$category] ?? null,
                        'question_number' => $number,
                        'is_reversed' => $isReversed,
                    ],
                ]);

                $this->command->info("Created question #{$number}: {$text}");

                // Create options for this question
                foreach ($likertOptions as $value => $optionText) {
                    // For reversed questions, we invert the scores
                    $score = $isReversed ? (6 - $value) : $value;

                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'order' => $value,
                        'score' => $score,
                    ]);
                }
            }

            $this->command->info('Added all EI questions with appropriate scoring!');
        });

        // Now create the career satisfaction questions
        $careerSatisfactionQuestions = [
            'I am satisfied with the success I have achieved in my career.',
            'I am satisfied with the progress I have made toward meeting my overall career goals.',
            'I am satisfied with the progress I have made toward meeting my goals for income.',
            'I am satisfied with the progress I have made toward meeting my goals for advancement.',
            'I am satisfied with the progress I have made toward meeting my goals for the development of new skills.',
        ];

        DB::transaction(function () use ($survey, $likertType, $careerSatisfactionQuestions) {
            // Check if we already have career satisfaction questions
            $existingCount = Question::where('survey_id', $survey->id)
                ->where('question_text', 'like', '%satisfied%')
                ->count();

            if ($existingCount >= count($careerSatisfactionQuestions)) {
                $this->command->info('Career satisfaction questions already exist. Skipping creation.');
                return;
            }

            // Get the current highest order value
            $maxOrder = Question::where('survey_id', $survey->id)->max('order') ?? 0;
            $order = $maxOrder + 1;

            foreach ($careerSatisfactionQuestions as $text) {
                $question = Question::create([
                    'survey_id' => $survey->id,
                    'question_type_id' => $likertType->id,
                    'question_text' => $text,
                    'help_text' => null,
                    'is_required' => false,
                    'order' => $order++,
                    'settings' => [
                        'category' => 'career_satisfaction',
                    ],
                ]);

                $this->command->info("Created career satisfaction question: {$text}");

                // Create the satisfaction scale options
                $satisfactionOptions = [
                    1 => 'Strongly Disagree',
                    2 => 'Disagree',
                    3 => 'Neutral',
                    4 => 'Agree',
                    5 => 'Strongly Agree',
                ];

                foreach ($satisfactionOptions as $value => $optionText) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'order' => $value,
                        'score' => $value,
                    ]);
                }
            }

            $this->command->info('Added all career satisfaction questions!');
        });

        // Create demographic questions
        $demographicQuestions = [
            [
                'text' => 'Are you a member of CPA Canada?',
                'type' => 'multiple-choice',
                'options' => [
                    'Yes' => null,
                    'No' => null,
                ],
            ],
            [
                'text' => 'Please indicate the year you were born.',
                'type' => 'dropdown',
                'options' => array_combine(
                    range(1940, 2005),
                    range(1940, 2005)
                ),
            ],
            [
                'text' => 'Please select the gender you are most comfortable disclosing.',
                'type' => 'dropdown',
                'options' => [
                    'Male' => null,
                    'Female' => null,
                    'Other' => null,
                    'Prefer not to disclose' => null,
                ],
            ],
            // Add other demographic questions here...
        ];

        DB::transaction(function () use ($survey, $multipleChoice, $dropdown, $demographicQuestions) {
            // Check if we already have demographic questions
            $existingCount = Question::where('survey_id', $survey->id)
                ->whereNotIn('question_text', ['%satisfied%', '%emotional intelligence%'])
                ->count();

            if ($existingCount >= count($demographicQuestions)) {
                $this->command->info('Demographic questions already exist. Skipping creation.');
                return;
            }

            // Get question types
            $types = [
                'multiple-choice' => $multipleChoice->id,
                'dropdown' => $dropdown->id,
            ];

            // Get the current highest order value
            $maxOrder = Question::where('survey_id', $survey->id)->max('order') ?? 0;
            $order = $maxOrder + 1;

            foreach ($demographicQuestions as $questionData) {
                $question = Question::create([
                    'survey_id' => $survey->id,
                    'question_type_id' => $types[$questionData['type']],
                    'question_text' => $questionData['text'],
                    'help_text' => $questionData['help_text'] ?? null,
                    'is_required' => $questionData['required'] ?? false,
                    'order' => $order++,
                    'settings' => [
                        'category' => 'demographic',
                    ],
                ]);

                $this->command->info("Created demographic question: {$questionData['text']}");

                // Create options
                $optionOrder = 1;
                foreach ($questionData['options'] as $optionText => $score) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'order' => $optionOrder++,
                        'score' => $score,
                    ]);
                }
            }

            $this->command->info('Added all demographic questions!');
        });
    }
}

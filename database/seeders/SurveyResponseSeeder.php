<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\ResponseAnswer;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SurveyResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all active surveys
        $surveys = Survey::where('is_active', true)->get();

        foreach ($surveys as $survey) {
            // Generate between 10-30 responses for each survey
            $responseCount = $faker->numberBetween(10, 30);

            for ($i = 0; $i < $responseCount; $i++) {
                // Create a survey response
                $response = SurveyResponse::create([
                    'survey_id' => $survey->id,
                    'completion_code' => SurveyResponse::generateUniqueCompletionCode(),
                    'demographic_data' => $this->generateDemographicData($faker),
                    'completed_at' => $faker->dateTimeBetween('-30 days', 'now'),
                    'ip_address' => $faker->ipv4,
                    'user_agent' => $faker->userAgent,
                    'is_winner' => $faker->boolean(10), // 10% chance of being a winner
                ]);

                // Add answers for this response
                $this->createAnswersForResponse($response, $survey, $faker);
            }
        }
    }

    /**
     * Generate random demographic data
     */
    private function generateDemographicData($faker): array
    {
        return [
            'birth_year' => (string) $faker->numberBetween(1960, 1995),
            'gender' => $faker->randomElement(['male', 'female', 'other', 'not_disclosed']),
            'languages' => $faker->randomElements(['english', 'french', 'indigenous', 'other'], $faker->numberBetween(1, 3)),
            'province' => $faker->randomElement(['AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'ON', 'PE', 'QC', 'SK']),
            'legacy_designation' => $faker->randomElement(['CGA', 'CA', 'CMA', 'other', 'none']),
            'years_designation' => (string) $faker->numberBetween(1, 25),
            'industry' => $faker->randomElement([
                'Public Practice',
                'Private Corporation',
                'Publicly traded Corporation',
                'Education',
                'Government',
                'Not For Profit',
                'Other'
            ]),
            'position' => $faker->randomElement([
                'Junior or entry level roles',
                'Intermediate roles with no supervision',
                'Manager or supervisor',
                'Senior Manager roles',
                'Director or VP roles',
                'Instructor, lecturer, or professor roles',
                'President, CEO, or owner of non-public accounting organisation',
                'Other'
            ]),
            'organization_size' => (string) $faker->numberBetween(1, 10000),
            'yearly_compensation' => (string) $faker->numberBetween(50000, 250000)
        ];
    }

    /**
     * Create answers for a survey response
     */
    private function createAnswersForResponse(SurveyResponse $response, Survey $survey, $faker): void
    {
        // Get all questions for this survey
        $questions = Question::where('survey_id', $survey->id)->get();

        foreach ($questions as $question) {
            // Skip non-question instructions
            if (strpos(strtolower($question->question_text), 'section below') !== false) {
                continue;
            }

            // Create answer based on question type
            switch ($question->questionType->slug) {
                case 'text':
                    ResponseAnswer::create([
                        'survey_response_id' => $response->id,
                        'question_id' => $question->id,
                        'answer_text' => $faker->sentence,
                        'selected_options' => null,
                    ]);
                    break;

                case 'textarea':
                    ResponseAnswer::create([
                        'survey_response_id' => $response->id,
                        'question_id' => $question->id,
                        'answer_text' => $faker->paragraph,
                        'selected_options' => null,
                    ]);
                    break;

                case 'number':
                    ResponseAnswer::create([
                        'survey_response_id' => $response->id,
                        'question_id' => $question->id,
                        'answer_text' => (string) $faker->numberBetween(1, 50),
                        'selected_options' => null,
                    ]);
                    break;

                case 'date':
                    ResponseAnswer::create([
                        'survey_response_id' => $response->id,
                        'question_id' => $question->id,
                        'answer_text' => $faker->date,
                        'selected_options' => null,
                    ]);
                    break;

                case 'multiple-choice':
                case 'dropdown':
                    // Get a random option id from this question
                    $optionId = $question->options->random()->id;

                    ResponseAnswer::create([
                        'survey_response_id' => $response->id,
                        'question_id' => $question->id,
                        'answer_text' => null,
                        'selected_options' => [$optionId],
                    ]);
                    break;

                case 'checkbox':
                    // Get 1-3 random option ids
                    $optionIds = $question->options
                        ->random($faker->numberBetween(1, min(3, $question->options->count())))
                        ->pluck('id')
                        ->toArray();

                    ResponseAnswer::create([
                        'survey_response_id' => $response->id,
                        'question_id' => $question->id,
                        'answer_text' => null,
                        'selected_options' => $optionIds,
                    ]);
                    break;

                case 'likert-scale':
                    // Get a random option id
                    $optionId = $question->options->random()->id;

                    ResponseAnswer::create([
                        'survey_response_id' => $response->id,
                        'question_id' => $question->id,
                        'answer_text' => null,
                        'selected_options' => [$optionId],
                    ]);
                    break;
            }
        }
    }
}

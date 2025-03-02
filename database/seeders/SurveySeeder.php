<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create CPA EI Survey
        $eiSurvey = Survey::create([
            'title' => 'EI and Career Success of CPAs',
            'description' => 'This survey aims to understand the relationship between emotional intelligence (EI) and career success among Certified Public Accountants.',
            'is_active' => true,
            'starts_at' => now()->subDays(30),
            'ends_at' => now()->addDays(60),
        ]);

        // Add demographic questions (for demonstration purposes)
        $this->createDemographicQuestions($eiSurvey);

        // Add career satisfaction questions
        $this->createCareerSatisfactionQuestions($eiSurvey);

        // Create additional sample surveys
        $this->createSampleSurveys();
    }

    /**
     * Create demographic questions for the survey
     */
    private function createDemographicQuestions(Survey $survey): void
    {
        $textType = QuestionType::where('slug', 'text')->first();
        $numberType = QuestionType::where('slug', 'number')->first();
        $dropdownType = QuestionType::where('slug', 'dropdown')->first();
        $checkboxType = QuestionType::where('slug', 'checkbox')->first();

        // Year of birth question
        Question::create([
            'survey_id' => $survey->id,
            'question_type_id' => $numberType->id,
            'question_text' => 'Please indicate the year you were born',
            'help_text' => 'Use numbers only (e.g., 1980)',
            'is_required' => true,
            'order' => 1,
        ]);

        // Gender question
        $genderQuestion = Question::create([
            'survey_id' => $survey->id,
            'question_type_id' => $dropdownType->id,
            'question_text' => 'Please select the gender you are most comfortable disclosing',
            'is_required' => true,
            'order' => 2,
        ]);
        // Gender options
        $genderOptions = ['Male', 'Female', 'Other', 'Prefer not to disclose'];
        foreach ($genderOptions as $index => $option) {
            QuestionOption::create([
                'question_id' => $genderQuestion->id,
                'option_text' => $option,
                'order' => $index + 1,
            ]);
        }

        // Languages question
        $languageQuestion = Question::create([
            'survey_id' => $survey->id,
            'question_type_id' => $checkboxType->id,
            'question_text' => 'Please select the languages you are fluent in',
            'is_required' => true,
            'order' => 3,
        ]);

        // Language options
        $languageOptions = ['English', 'French', 'Canadian Indigenous languages', 'Other'];
        foreach ($languageOptions as $index => $option) {
            QuestionOption::create([
                'question_id' => $languageQuestion->id,
                'option_text' => $option,
                'order' => $index + 1,
            ]);
        }

        // Legacy designation question
        $designationQuestion = Question::create([
            'survey_id' => $survey->id,
            'question_type_id' => $dropdownType->id,
            'question_text' => 'Please indicate the legacy designation that you hold',
            'is_required' => true,
            'order' => 4,
        ]);

        // Designation options
        $designationOptions = ['CGA', 'CA', 'CMA', 'Other', 'None'];
        foreach ($designationOptions as $index => $option) {
            QuestionOption::create([
                'question_id' => $designationQuestion->id,
                'option_text' => $option,
                'order' => $index + 1,
            ]);
        }

        // Years since designation
        Question::create([
            'survey_id' => $survey->id,
            'question_type_id' => $numberType->id,
            'question_text' => 'Please indicate the years since you obtained your designation (including legacy designations)',
            'help_text' => 'Use numbers only',
            'is_required' => true,
            'order' => 5,
        ]);

        // Industry type question
        $industryQuestion = Question::create([
            'survey_id' => $survey->id,
            'question_type_id' => $dropdownType->id,
            'question_text' => 'Please indicate the type of industry that best fits where you work',
            'help_text' => 'If you are retired or unemployed, indicate the industry at your retirement or your last employer',
            'is_required' => true,
            'order' => 6,
        ]);

        // Industry options
        $industryOptions = [
            'Public Practice',
            'Private Corporation',
            'Publicly traded Corporation',
            'Education',
            'Government',
            'Not For Profit',
            'Other'
        ];

        foreach ($industryOptions as $index => $option) {
            QuestionOption::create([
                'question_id' => $industryQuestion->id,
                'option_text' => $option,
                'order' => $index + 1,
            ]);
        }
    }

    /**
     * Create career satisfaction questions for the survey
     */
    private function createCareerSatisfactionQuestions(Survey $survey): void
    {
        $likertType = QuestionType::where('slug', 'likert-scale')->first();

        // Career satisfaction section intro
        $satisfactionIntro = Question::create([
            'survey_id' => $survey->id,
            'question_type_id' => QuestionType::where('slug', 'text')->first()->id,
            'question_text' => 'The section below is based on the Perceived Career Satisfaction Scale (Greenhaus, Parasuraman, & Wormley, 1990). Please indicate the extent to which the following statements apply to your situation.',
            'is_required' => false,
            'order' => 7,
        ]);

        // Career satisfaction questions
        $satisfactionQuestions = [
            'I am satisfied with the success I have achieved in my career.',
            'I am satisfied with the progress I have made towards meeting my overall career goals.',
            'I am satisfied with the progress I have made towards meeting my goals for income.',
            'I am satisfied with the progress I have made towards meeting my goals for advancement.',
            'I am satisfied with the progress I have made towards meeting my goals for the development of new skills.',
            'I am satisfied with the degree to which I have achieved a healthy work-life balance.',
        ];

        // Likert scale options
        $likertOptions = [
            'Strongly Disagree',
            'Disagree to some extent',
            'Uncertain',
            'Agree to some extent',
            'Strongly agree',
        ];

        // Create each satisfaction question
        foreach ($satisfactionQuestions as $index => $questionText) {
            $question = Question::create([
                'survey_id' => $survey->id,
                'question_type_id' => $likertType->id,
                'question_text' => $questionText,
                'is_required' => true,
                'order' => 8 + $index,
            ]);

            // Add the likert scale options to each question
            foreach ($likertOptions as $optionIndex => $optionText) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionText,
                    'order' => $optionIndex + 1,
                    'score' => $optionIndex + 1, // Score from 1 to 5
                ]);
            }
        }
    }

    /**
     * Create additional sample surveys
     */
    private function createSampleSurveys(): void
    {
        // Sample survey 1: Professional Development
        $pdSurvey = Survey::create([
            'title' => 'Professional Development Needs Assessment',
            'description' => 'Help us understand your professional development needs and preferences to better serve the CPA community.',
            'is_active' => true,
            'starts_at' => now()->subDays(15),
            'ends_at' => now()->addDays(45),
        ]);

        // Add a few sample questions to this survey
        $multipleChoiceType = QuestionType::where('slug', 'multiple-choice')->first();
        $checkboxType = QuestionType::where('slug', 'checkbox')->first();
        $textareaType = QuestionType::where('slug', 'textarea')->first();

        // Sample question 1
        $pdq1 = Question::create([
            'survey_id' => $pdSurvey->id,
            'question_type_id' => $multipleChoiceType->id,
            'question_text' => 'How often do you participate in professional development activities?',
            'is_required' => true,
            'order' => 1,
        ]);

        $pdq1Options = ['Monthly', 'Quarterly', 'Annually', 'Only when required', 'Never'];
        foreach ($pdq1Options as $index => $option) {
            QuestionOption::create([
                'question_id' => $pdq1->id,
                'option_text' => $option,
                'order' => $index + 1,
            ]);
        }

        // Sample question 2
        $pdq2 = Question::create([
            'survey_id' => $pdSurvey->id,
            'question_type_id' => $checkboxType->id,
            'question_text' => 'Which professional development topics are you most interested in? (Select all that apply)',
            'is_required' => true,
            'order' => 2,
        ]);

        $pdq2Options = [
            'Tax Updates',
            'Accounting Standards',
            'Leadership & Management',
            'Technology & Automation',
            'Ethics & Professional Responsibility',
            'Soft Skills & Communication',
            'Industry-Specific Knowledge'
        ];

        foreach ($pdq2Options as $index => $option) {
            QuestionOption::create([
                'question_id' => $pdq2->id,
                'option_text' => $option,
                'order' => $index + 1,
            ]);
        }

        // Sample question 3
        Question::create([
            'survey_id' => $pdSurvey->id,
            'question_type_id' => $textareaType->id,
            'question_text' => 'What specific professional development topics would you like to see offered in the coming year?',
            'is_required' => false,
            'order' => 3,
        ]);

        // Sample survey 2: Work-Life Balance
        $wlbSurvey = Survey::create([
            'title' => 'Work-Life Balance in Accounting',
            'description' => 'This survey explores work-life balance challenges and strategies among accounting professionals.',
            'is_active' => true,
            'starts_at' => now(),
            'ends_at' => now()->addDays(90),
        ]);

        // Let's add some sample questions to this survey as well

    }
}

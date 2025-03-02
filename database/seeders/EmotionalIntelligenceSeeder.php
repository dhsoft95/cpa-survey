<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Survey;
use Illuminate\Database\Seeder;

class EmotionalIntelligenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Emotional Intelligence Survey
        $eiSurvey = Survey::create([
            'title' => 'Emotional Intelligence Self-Assessment',
            'description' => 'This survey measures how often you demonstrate emotionally intelligent behaviors at work. There are no right or wrong answers. However, it is essential that your responses truly reflect your beliefs regarding how often you demonstrate the behavior in question.',
            'is_active' => true,
            'starts_at' => now(),
            'ends_at' => now()->addMonths(6),
        ]);

        // Get the Likert scale question type
        $likertType = QuestionType::where('slug', 'likert-scale')->firstOrFail();

        // Instructions question (text type)
        $textType = QuestionType::where('slug', 'text')->firstOrFail();

        // Create instruction text
        Question::create([
            'survey_id' => $eiSurvey->id,
            'question_type_id' => $textType->id,
            'question_text' => 'The Genos EI Self Assessment has been designed to measure how often you believe you demonstrate emotionally intelligent behaviours at work. There are no right or wrong answers. Please indicate on the response scale how often you believe you demonstrate the behavior in question based on your typical behavior.',
            'help_text' => 'When considering a response it is important not to think of the way you behaved in any one situation, rather your responses should be based on your typical behaviour.',
            'is_required' => false,
            'order' => 1,
        ]);

        // Create the Likert scale options (these will be the same for all questions)
        $likertOptions = [
            ['option_text' => 'Almost Never', 'order' => 1, 'score' => 1],
            ['option_text' => 'Seldom', 'order' => 2, 'score' => 2],
            ['option_text' => 'Sometimes', 'order' => 3, 'score' => 3],
            ['option_text' => 'Usually', 'order' => 4, 'score' => 4],
            ['option_text' => 'Almost Always', 'order' => 5, 'score' => 5],
        ];

        // Emotional Intelligence questions from the assessment
        $eiQuestions = [
            // Emotional Self-Awareness
            ['question_text' => 'I am aware of things that upset me at work.', 'category' => 'emotional self-awareness'],
            ['question_text' => 'I am aware of when I am feeling negative at work.', 'category' => 'emotional self-awareness'],
            ['question_text' => 'I am aware of how my feelings influence the way I respond to colleagues.', 'category' => 'emotional self-awareness'],
            ['question_text' => 'I am aware of my body language at work.', 'category' => 'emotional self-awareness'],
            ['question_text' => 'I am aware of my mood state at work.', 'category' => 'emotional self-awareness'],
            ['question_text' => 'I am aware of the tone of voice I use to communicate with others at work.', 'category' => 'emotional self-awareness'],
            ['question_text' => 'I fail to recognise how my feelings drive my behaviour at work.', 'category' => 'emotional self-awareness', 'reverse' => true],
            ['question_text' => 'I am aware of how my feelings influence the decisions I make at work.', 'category' => 'emotional self-awareness'],
            ['question_text' => 'I find it difficult to identify my feelings on issues at work.', 'category' => 'emotional self-awareness', 'reverse' => true],
            ['question_text' => 'I am aware of things that make me feel positive at work.', 'category' => 'emotional self-awareness'],

            // Emotional Expression
            ['question_text' => 'I effectively express how I feel about issues at work.', 'category' => 'emotional expression'],
            ['question_text' => 'I express how I feel to the wrong people at work.', 'category' => 'emotional expression', 'reverse' => true],
            ['question_text' => 'I express positive emotions I experience at work inappropriately.', 'category' => 'emotional expression', 'reverse' => true],
            ['question_text' => 'I express how I feel at the appropriate time.', 'category' => 'emotional expression'],
            ['question_text' => 'I provide positive feedback to colleagues.', 'category' => 'emotional expression'],
            ['question_text' => 'When I am happy at work I express how I feel effectively.', 'category' => 'emotional expression'],
            ['question_text' => 'When someone upsets me at work I express how I feel effectively.', 'category' => 'emotional expression'],
            ['question_text' => 'I effectively express optimism at work.', 'category' => 'emotional expression'],
            ['question_text' => 'I have trouble finding the right words to express how I feel at work.', 'category' => 'emotional expression', 'reverse' => true],
            ['question_text' => 'When I get frustrated with something at work I discuss my frustration appropriately.', 'category' => 'emotional expression'],

            // Emotional Awareness of Others
            ['question_text' => 'I am aware of the things that make colleagues feel satisfied at work.', 'category' => 'emotional awareness of others'],
            ['question_text' => 'I find it difficult to identify the things that motivate people at work.', 'category' => 'emotional awareness of others', 'reverse' => true],
            ['question_text' => 'I fail to identify the way people respond to me when building rapport.', 'category' => 'emotional awareness of others', 'reverse' => true],
            ['question_text' => 'I understand the things that cause others to feel engaged at work.', 'category' => 'emotional awareness of others'],
            ['question_text' => 'I demonstrate an understanding of others\' feelings at work.', 'category' => 'emotional awareness of others'],
            ['question_text' => 'I fail to recognise when colleagues\' emotional reactions are inappropriate.', 'category' => 'emotional awareness of others', 'reverse' => true],
            ['question_text' => 'I identify others\' non verbal emotional cues (e.g., body language).', 'category' => 'emotional awareness of others'],
            ['question_text' => 'I understand the things that make people feel optimistic at work.', 'category' => 'emotional awareness of others'],
            ['question_text' => 'I understand what makes people feel valued at work.', 'category' => 'emotional awareness of others'],
            ['question_text' => 'I identify the way people feel about issues at work.', 'category' => 'emotional awareness of others'],

            // Emotional Reasoning
            ['question_text' => 'I ask others how they feel about different solutions when problem solving at work.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I demonstrate to others that I have considered their feelings in decisions I make at work.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I consider the organisation\'s values when making important decisions.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I demonstrate to others that I have considered my own feelings when making decisions at work.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I communicate decisions at work in a way that captures others\' attention.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I gain stakeholders\' commitment to decisions I make at work.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I appropriately communicate decisions to stakeholders.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I consider the way others may react to decisions when communicating them.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I take into account both technical information and the way I feel about different choices when making decisions at work.', 'category' => 'emotional reasoning'],
            ['question_text' => 'I focus solely on facts and technical information related to problems when trying to derive a solution.', 'category' => 'emotional reasoning', 'reverse' => true],

            // Add more questions for the remaining categories
            // Emotional Self-Management
            ['question_text' => 'I take criticism from colleagues personally.', 'category' => 'emotional self-management', 'reverse' => true],
            ['question_text' => 'I engage in activities that make me feel positive at work.', 'category' => 'emotional self-management'],
            ['question_text' => 'I ruminate about things that anger me at work.', 'category' => 'emotional self-management', 'reverse' => true],
            ['question_text' => 'I effectively deal with things that annoy me at work.', 'category' => 'emotional self-management'],
            ['question_text' => 'I appropriately respond to colleagues who frustrate me at work.', 'category' => 'emotional self-management'],
            ['question_text' => 'I demonstrate positive moods and emotions at work.', 'category' => 'emotional self-management'],
            ['question_text' => 'I quickly adjust to new conditions at work.', 'category' => 'emotional self-management'],
            ['question_text' => 'I fail to handle stressful situations at work effectively.', 'category' => 'emotional self-management', 'reverse' => true],
            ['question_text' => 'I respond to events that frustrate me appropriately.', 'category' => 'emotional self-management'],
            ['question_text' => 'I explore the causes of things that upset me at work.', 'category' => 'emotional self-management'],

            // Emotional Management of Others
            ['question_text' => 'I create a positive working environment for others.', 'category' => 'emotional management of others'],
            ['question_text' => 'I fail to get colleagues to cooperate.', 'category' => 'emotional management of others', 'reverse' => true],
            ['question_text' => 'I motivate others toward work related goals.', 'category' => 'emotional management of others'],
            ['question_text' => 'When necessary I effectively demonstrate empathy to colleagues.', 'category' => 'emotional management of others'],
            ['question_text' => 'I am effective in helping others feel positive at work.', 'category' => 'emotional management of others'],
            ['question_text' => 'I help people find effective ways of responding to upsetting events.', 'category' => 'emotional management of others'],
            ['question_text' => 'When colleagues are disappointed about something I help them feel differently about the situation.', 'category' => 'emotional management of others'],
            ['question_text' => 'I help people deal with issues that cause them frustration at work.', 'category' => 'emotional management of others'],
            ['question_text' => 'I don\'t know what to do or say when colleagues get upset at work.', 'category' => 'emotional management of others', 'reverse' => true],
            ['question_text' => 'I fail to resolve emotional situations at work effectively.', 'category' => 'emotional management of others', 'reverse' => true],

            // Emotional Self-Control
            ['question_text' => 'I demonstrate enthusiasm appropriately at work.', 'category' => 'emotional self-control'],
            ['question_text' => 'I remain focused when anxious about something at work.', 'category' => 'emotional self-control'],
            ['question_text' => 'I behave inappropriately when angry at work.', 'category' => 'emotional self-control', 'reverse' => true],
            ['question_text' => 'I demonstrate excitement at work appropriately.', 'category' => 'emotional self-control'],
            ['question_text' => 'When I am under stress I become impulsive.', 'category' => 'emotional self-control', 'reverse' => true],
            ['question_text' => 'I fail to control my temper at work.', 'category' => 'emotional self-control', 'reverse' => true],
            ['question_text' => 'I hold back my initial reaction when something upsets me at work.', 'category' => 'emotional self-control'],
            ['question_text' => 'I am impatient when things don\'t get done as planned at work.', 'category' => 'emotional self-control', 'reverse' => true],
            ['question_text' => 'When upset at work I still think clearly.', 'category' => 'emotional self-control'],
            ['question_text' => 'I fail to keep calm in difficult situations at work.', 'category' => 'emotional self-control', 'reverse' => true],
        ];

        // Create the questions with their options
        foreach ($eiQuestions as $index => $eiQuestion) {
            $question = Question::create([
                'survey_id' => $eiSurvey->id,
                'question_type_id' => $likertType->id,
                'question_text' => $eiQuestion['question_text'],
                'help_text' => $eiQuestion['category'] ?? null, // Store the category in help_text for now
                'is_required' => true,
                'order' => $index + 2, // Start after the instruction question
                'settings' => [
                    'category' => $eiQuestion['category'],
                    'reverse' => $eiQuestion['reverse'] ?? false,
                ],
            ]);

            // Create options for each question
            foreach ($likertOptions as $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'order' => $option['order'],
                    'score' => $option['score'],
                ]);
            }
        }

        // Add a final question for score calculation
        Question::create([
            'survey_id' => $eiSurvey->id,
            'question_type_id' => $textType->id,
            'question_text' => 'Thank you for completing the Emotional Intelligence Self-Assessment. Your responses will be analyzed to provide you with a score for each of the 7 dimensions of emotional intelligence: Emotional Self-Awareness, Emotional Expression, Emotional Awareness of Others, Emotional Reasoning, Emotional Self-Management, Emotional Management of Others, and Emotional Self-Control.',
            'is_required' => false,
            'order' => count($eiQuestions) + 2,
        ]);
    }
}

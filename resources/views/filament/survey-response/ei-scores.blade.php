<div>
    @if(isset($getRecord()->demographic_data['ei_scores']))
        <div class="mb-4">
            <h3 class="text-xl font-semibold dark:text-gray-200">
                Total EI Score:
                <span class="text-blue-600 dark:text-blue-400">
                    {{ $getRecord()->demographic_data['total_ei_score'] }}
                </span>
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Based on the GENOS Emotional Intelligence framework
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($getRecord()->demographic_data['ei_scores'] as $key => $score)
                @php
                    // Calculate percentage for progress bar
                    $percentage = $score['max'] > 0 ? round(($score['raw'] / $score['max']) * 100) : 0;

                    // Define color based on category
                    $colors = [
                        'esa' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700 dark:text-blue-400', 'light' => 'bg-blue-50 dark:bg-blue-900/30'],
                        'ee' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-700 dark:text-indigo-400', 'light' => 'bg-indigo-50 dark:bg-indigo-900/30'],
                        'eao' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-700 dark:text-purple-400', 'light' => 'bg-purple-50 dark:bg-purple-900/30'],
                        'er' => ['bg' => 'bg-green-500', 'text' => 'text-green-700 dark:text-green-400', 'light' => 'bg-green-50 dark:bg-green-900/30'],
                        'esm' => ['bg' => 'bg-teal-500', 'text' => 'text-teal-700 dark:text-teal-400', 'light' => 'bg-teal-50 dark:bg-teal-900/30'],
                        'emo' => ['bg' => 'bg-cyan-500', 'text' => 'text-cyan-700 dark:text-cyan-400', 'light' => 'bg-cyan-50 dark:bg-cyan-900/30'],
                        'esc' => ['bg' => 'bg-rose-500', 'text' => 'text-rose-700 dark:text-rose-400', 'light' => 'bg-rose-50 dark:bg-rose-900/30'],
                    ];

                    $color = $colors[$key] ?? ['bg' => 'bg-gray-500', 'text' => 'text-gray-700 dark:text-gray-400', 'light' => 'bg-gray-50 dark:bg-gray-900/30'];
                @endphp

                <div class="border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden bg-white dark:bg-gray-800 transition-all hover:shadow-md">
                    <div class="p-4">
                        <div class="font-medium {{ $color['text'] }} mb-2">{{ $score['name'] }}</div>

                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Score: {{ $score['raw'] }}/{{ $score['max'] }}</span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $percentage }}%</span>
                        </div>

                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-3">
                            <div class="{{ $color['bg'] }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>

                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $score['questions_answered'] }} questions answered
                        </div>
                    </div>

                    <div class="{{ $color['light'] }} px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-xs">
                            @if($key == 'esa')
                                Awareness of your own emotions
                            @elseif($key == 'ee')
                                Effectively expressing emotions
                            @elseif($key == 'eao')
                                Perceiving others' emotions
                            @elseif($key == 'er')
                                Using emotions in decision making
                            @elseif($key == 'esm')
                                Managing your own emotions
                            @elseif($key == 'emo')
                                Positively influencing others' emotions
                            @elseif($key == 'esc')
                                Controlling strong emotions effectively
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 p-4 rounded-lg border border-blue-100 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/20 text-sm text-gray-600 dark:text-gray-400">
            <p class="font-medium mb-1">What does this mean?</p>
            <p>These scores provide insights about emotional intelligence strengths and areas for development. Higher scores indicate stronger capabilities in each dimension.</p>
        </div>
    @else
        <div class="p-8 text-center text-gray-500 dark:text-gray-400 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium mb-1">No EI Scores Available</h3>
            <p>This respondent has not completed the emotional intelligence section of the survey.</p>
        </div>
    @endif
</div>

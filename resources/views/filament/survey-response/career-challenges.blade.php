<div class="p-5 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
    <div class="flex items-center mb-4">
        <div class="mr-3 text-blue-500 dark:text-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Career Challenges & Opportunities</h3>
    </div>
    @if(isset($getRecord()->demographic_data['career_challenges']) && !empty($getRecord()->demographic_data['career_challenges']))
        <div class="relative">
            <div class="absolute top-0 bottom-0 left-0 w-1 bg-blue-100 dark:bg-blue-900/40 rounded-full"></div>
            <div class="whitespace-pre-wrap text-gray-700 dark:text-gray-300 p-4 pl-6 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-gray-200 dark:border-gray-700">
                {{ $getRecord()->demographic_data['career_challenges'] }}
            </div>
        </div>

        <div class="mt-3 flex items-center text-xs text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Respondent's personal thoughts and opinions
        </div>
    @else
        <div class="flex flex-col items-center justify-center p-8 text-center bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-500 dark:text-gray-400">
                No information provided by the respondent
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                This section is optional in the survey
            </p>
        </div>
    @endif
</div>

<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12 text-center">
        <div class="inline-block bg-indigo-100 rounded-full p-3 mb-4">
            <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Shape the Future of CPA</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
            Share your insights through our surveys and contribute to the profession's evolution. Earn rewards while making an impact!
        </p>
    </div>

    @if($surveys->isEmpty())
        <div class="bg-white rounded-xl shadow-lg p-8 text-center max-w-2xl mx-auto">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-gray-500 text-xl font-light">New surveys coming soon!<br>Stay tuned for opportunities to share your perspective.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($surveys as $survey)
                <div class="bg-white rounded-xl shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl group">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 tracking-tight">{{ $survey->title }}</h2>
                        </div>

                        <p class="text-gray-600 line-clamp-3 mb-6 leading-relaxed">
                            {{ $survey->description }}
                        </p>

                        <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span>{{ $survey->questions->count() }}</span>
                            </div>
                            <a href="{{ route('surveys.take', $survey) }}"
                               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-br from-blue-500 to-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:from-blue-600 hover:to-blue-700 transform transition-all duration-200 hover:-translate-y-0.5 shadow-sm hover:shadow-md">
                                Start Survey
                                <svg class="w-4 h-4 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

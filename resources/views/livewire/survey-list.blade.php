<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section with Updated Title and Description -->
    <div class="mb-12 text-center">
        <div class="inline-block bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full p-4 mb-5 shadow-lg">
            <svg class="w-14 h-14 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">EI CPA Career Success Survey</h1>
{{--        <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">--}}
{{--            Please share your valuable professional input and help us gather data needed to help solidify our understanding of exactly how emotional intelligence (EI) impacts career success of Chartered Professional Accountants (CPAs).--}}
{{--            <span class="block mt-2 font-medium">Complete the survey for a chance to win rewards and receive insights on EI's impact!</span>--}}
{{--        </p>--}}
    </div>
    @if($surveys->isEmpty())
        <!-- Empty State with Updated Text -->
        <div class="bg-white rounded-2xl shadow-xl p-10 text-center max-w-2xl mx-auto border border-gray-100">
            <div class="bg-blue-50 rounded-full p-5 w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">No Active Surveys</h2>
            <p class="text-gray-500 text-lg font-light">There are currently no active surveys available.<br>Please check back later for future research opportunities.</p>
            <div class="mt-8">
                <button class="px-5 py-3 bg-gray-100 rounded-lg text-gray-600 font-medium hover:bg-gray-200 transition-all">
                    Check Back Soon
                </button>
            </div>
        </div>
    @else
        <!-- Survey Cards Grid with Updated Wording -->
        <div class="grid grid-cols-1 md:grid-cols- lg:grid-cols-1 gap-8">
            @foreach($surveys as $survey)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl group border border-gray-100">
                    <!-- Card Header with Badge -->
                    <div class="relative">
                        <div class="h-6 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                        <div class="absolute -bottom-8 left-6">
                            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6 pt-12">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight mb-3 group-hover:text-blue-600 transition-colors">{{ $survey->title }}</h2>

                        <p class="text-gray-600 line-clamp-3 mb-6 leading-relaxed">
                            {{ $survey->description }}
                        </p>

                        <!-- Card Footer -->
                        <div class="flex justify-between items-center border-t border-gray-100 pt-4 mt-4">
                            <div class="flex items-center space-x-4">
                                <!-- Questions Count -->
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <span>{{ $survey->questions->count() }} questions</span>
                                </div>

                                <!-- Time Estimate -->
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>~{{ ceil($survey->questions->count() / 5) }} min</span>
                                </div>
                            </div>

                            <!-- Prize Badge -->
                            <div class="bg-green-100 text-xs text-green-800 px-2 py-1 rounded-full">
                                <span class="font-medium">Prize Draw</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('surveys.take', $survey) }}"
                           class="mt-6 flex w-full items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl font-semibold text-white tracking-wide hover:from-blue-600 hover:to-indigo-700 transform transition-all duration-200 shadow-md hover:shadow-lg">
                            Participate
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bottom Info Section (Research Disclosure) -->
        <div class="p-6 bg-white border-b border-gray-100">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h2 class="font-bold text-gray-800 mb-2">About this Research</h2>
                <p class="text-gray-600 text-sm leading-relaxed">
                    This is a comprehensive PhD research study, by a Canadian CPA, through Andrews University. It investigates the influence of EI, moderated by variables such as age etc. Career success is measured both subjectively and objectively. <br> It is important that you complete all the questions as your responses will impact the validity of the findings.
                </p>
                <p class="text-gray-600 text-sm mt-2">
                    Participation is voluntary and all data collected will be used for research purposes only. For questions about this study, please contact <a href="mailto:jenipher@andrews.edu" class="text-blue-600 hover:underline">jenipher@andrews.edu</a>
                </p>
            </div>
        </div>
    @endif
</div>

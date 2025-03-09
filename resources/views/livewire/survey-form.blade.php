<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if($completionCode)
        <!-- Success Card with Scores -->
        <div class="bg-white shadow-xl rounded-2xl p-8 text-center transform transition-all duration-300 hover:shadow-2xl">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6 animate-bounce">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="mt-4 text-3xl font-bold text-gray-900 font-serif">Thank You!</h2>
            <p class="mt-2 text-lg text-gray-600">Your responses have been successfully recorded</p>
{{--            <p class="text-sm text-gray-500 mt-2">The submission of the questionnaire serves as a form of implied consent.</p>--}}

            @if($showScores)
                <!-- Score Results Section -->
                <div class="mt-8 p-6 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl border border-blue-100">
                    <h3 class="text-xl font-bold text-blue-700 mb-6">Your Emotional Intelligence Profile</h3>
                    <p class="text-gray-600 mb-6">Based on the GENOS Emotional Intelligence framework</p>

                    <!-- Total Score -->
                    <div class="bg-white rounded-lg p-4 shadow-md mb-6 flex items-center justify-between">
                        <div class="text-left">
                            <h4 class="font-bold text-lg text-gray-800">Overall EI Score</h4>
                            <p class="text-gray-500 text-sm">Based on your responses to EI questions</p>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-bold text-blue-600">
                                {{ $totalScore }}
                            </span>
                            <span class="text-sm text-gray-500"> points</span>
                        </div>
                    </div>

                    <!-- EI Category Scores -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Emotional Self-Awareness -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <h4 class="font-bold text-gray-800 mb-1">{{ $eiScores['esa']['name'] ?? 'Emotional Self-Awareness' }}</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Your score:</span>
                                <span class="font-bold text-blue-600">{{ $eiScores['esa']['raw'] ?? 0 }} points</span>
                            </div>
                        </div>

                        <!-- Emotional Expression -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <h4 class="font-bold text-gray-800 mb-1">{{ $eiScores['ee']['name'] ?? 'Emotional Expression' }}</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Your score:</span>
                                <span class="font-bold text-indigo-600">{{ $eiScores['ee']['raw'] ?? 0 }} points</span>
                            </div>
                        </div>

                        <!-- Emotional Awareness of Others -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <h4 class="font-bold text-gray-800 mb-1">{{ $eiScores['eao']['name'] ?? 'Emotional Awareness of Others' }}</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Your score:</span>
                                <span class="font-bold text-purple-600">{{ $eiScores['eao']['raw'] ?? 0 }} points</span>
                            </div>
                        </div>

                        <!-- Emotional Reasoning -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <h4 class="font-bold text-gray-800 mb-1">{{ $eiScores['er']['name'] ?? 'Emotional Reasoning' }}</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Your score:</span>
                                <span class="font-bold text-green-600">{{ $eiScores['er']['raw'] ?? 0 }} points</span>
                            </div>
                        </div>

                        <!-- Emotional Self-Management -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <h4 class="font-bold text-gray-800 mb-1">{{ $eiScores['esm']['name'] ?? 'Emotional Self-Management' }}</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Your score:</span>
                                <span class="font-bold text-teal-600">{{ $eiScores['esm']['raw'] ?? 0 }} points</span>
                            </div>
                        </div>

                        <!-- Emotional Management of Others -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <h4 class="font-bold text-gray-800 mb-1">{{ $eiScores['emo']['name'] ?? 'Emotional Management of Others' }}</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Your score:</span>
                                <span class="font-bold text-blue-600">{{ $eiScores['emo']['raw'] ?? 0 }} points</span>
                            </div>
                        </div>

                        <!-- Emotional Self-Control -->
                        <div class="bg-white rounded-lg p-4 shadow-sm md:col-span-2">
                            <h4 class="font-bold text-gray-800 mb-1">{{ $eiScores['esc']['name'] ?? 'Emotional Self-Control' }}</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Your score:</span>
                                <span class="font-bold text-red-600">{{ $eiScores['esc']['raw'] ?? 0 }} points</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 mt-4 bg-blue-50 p-4 rounded-lg">
                        <p class="font-medium mb-2">What does this mean?</p>
                        <p> Although the Genos Instrument is for research purposes only, if you answered all questions, your scores provide insights about strengths and areas to develop. At the end of the study, summary results will be shared to give you the opportunity to assess your score with those of your peers.</p>
                    </div>
                </div>
            @endif
            <div class="mt-8 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                <p class="text-sm text-gray-600 mb-2 font-medium">Your completion code:</p>
                <div class="flex items-center justify-center space-x-3">
                    <span class="text-3xl font-mono font-bold text-blue-700 tracking-wider bg-white px-4 py-2 rounded-lg shadow-inner">
                        {{ $completionCode }}
                    </span>
                    <button onclick="copyToClipboard('{{ $completionCode }}')"
                            class="p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200 group"
                            data-tippy-content="Copy to clipboard">
                        <svg class="h-6 w-6 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                    </button>
                </div>
                <p class="mt-4 text-sm text-gray-600 text-center max-w-md mx-auto leading-relaxed">
                    ✨ Keep this code safe! On April 7, 2025, check the awards section at the beginning of the survey and follow instructions to claim your gift card if you're a winner.
                </p>
            </div>
        </div>

    @elseif($showNonCpaThanks)
        <!-- Simple Thank You Card for Non-CPA Members -->
        <div class="bg-white shadow-xl rounded-2xl p-8 text-center transform transition-all duration-300 hover:shadow-2xl">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="mt-4 text-3xl font-bold text-gray-900 font-serif">Thank You!</h2>
            <p class="mt-2 text-lg text-gray-600">This survey is designed for CPA Canada members only.</p>
            <p class="mt-4 text-gray-600">We appreciate your interest in our research.</p>

            <div class="mt-8">
                <a href="/" class="inline-flex items-center px-6 py-3 border border-transparent rounded-full font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg transform transition hover:scale-105 duration-200">
                    Return to Home
                </a>
            </div>
        </div>
    @else
        <!-- Survey Container -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl">

            <!-- Progress Bar - Update to show 4 steps -->
            <div class="p-6 bg-white border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-500"
                             style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-600">
                        Step <span class="text-blue-600">{{ $currentStep }}</span> of {{ $totalSteps }}
                    </span>
                </div>
            </div>

            <!-- Loading Overlay - unchanged -->
            <div wire:loading.flex wire:target="nextStep, submitSurvey"
                 class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                <div class="bg-white rounded-lg p-8 max-w-md mx-auto text-center shadow-2xl">
                    <div class="flex justify-center mb-6">
                        <svg class="animate-spin h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Processing Your Response</h3>
                    <p class="text-gray-600 mb-2">Please wait while we calculate your results...</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div class="bg-blue-600 h-2.5 rounded-full animate-pulse" style="width: 100%"></div>
                    </div>
                    <p class="text-sm text-gray-500">This may take a few moments.</p>
                </div>
            </div>

            <div class="p-8">
                <form wire:submit.prevent="nextStep">
                    <!-- Step 1: Consent Form (unchanged) -->
                    @if($currentStep == 1)
                        <div class="mb-8 p-6 bg-white rounded-xl border border-blue-100 shadow-sm">

                            <p class="text-1xl">
                                This survey is for individuals who hold a Canadian Chartered Professional Accountant (CPA) Designation
                            </p>
                            <p class="text-1xl font-bold text-gray-90">The submission of the questionnaire serves as a form of implied consent. It also confirms that you read and understood the consent information
                                </p>
{{--                            <h2 class="text-1xl font-bold text-gray-900 font-serif border-l-4 border-blue-600 pl-4 mb-6">--}}
{{--                                Are you a CPA?--}}
{{--                            </h2>--}}
                            <div class="mt-8 space-y-4">
                                <h3 class="text-1xl font-bold text-gray-900 font-serif border-l-4 border-blue-600 pl-4 mb-6">
                                    Are you a member of CPA Canada?
                                </h3>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" wire:model="demographicData.is_cpa_member" value="yes"
                                               class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" wire:model="demographicData.is_cpa_member" value="no"
                                               class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-gray-700">No</span>
                                    </label>
                                </div>
                                @error('demographicData.is_cpa_member')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                        <!-- Step 2: CPA Membership check ONLY -->
                    @elseif($currentStep == 2)
                        <div class="mb-8 p-6 bg-white rounded-xl border border-blue-100 shadow-sm">
                            <h2 class="text-2xl font-bold text-gray-900 font-serif mb-4">Your Consent</h2>
                            <p class="text-2xl font-bold text-blue font-serif mb-4">Please read the following before beginning the survey</p>
                            <div class="prose prose-blue max-w-none text-gray-700">
                                <p class="mb-4">
                                    You are being invited to participate in a research study titled the Role of emotional Intelligence (EI) in the career success of professional accountants (CPAs). This study is being done by Jenipher Chitate (MBA, CPA, CMA) as part of a Doctorate Degree at Andrews University.
                                </p>
                                <p class="mb-4">
                                    Emotional intelligence is increasingly being recognized as an enabling capability required for success throughout a CPA's career. Despite this growing recognition, there are still gaps in knowledge as to how exactly EI impacts career success for the accountant. The purpose of the study is to provide evidence to help ascertain the role of EI in the career success of professional accountants.
                                </p>
                                <p class="mb-4">
                                    If you agree to take part in this study, you will be asked to complete an online questionnaire. This questionnaire will ask about biographical data, your perceived level of career success, and how you navigate through emotionally intelligent work-related situations. The whole survey should take you approximately 20 minutes to complete. The bulk of the questions pertain to the assessment of EI. The EI specific questions use a comprehensive externally validated and accepted tool specifically designed to assess EI in practical work-place contexts.
                                </p>
                                <p class="mb-4">
                                    We recognize that your time is extremely important. As a token of appreciation, the questionnaire has been setup so that you can potentially benefit from your participation. We believe that reading and responding to the EI specific questions will give you a greater sense of the type of work-related situations that are impacted by EI. Upon submission of the questionnaire, you will get your individual EI scores. You are encouraged to keep your EI score. At the end of the study, the summary of results will be shared to give you the opportunity to assess your score with those of your peers. <strong>Although you are free to skip any question, the accuracy of the EI score is dependent on the questions answered.</strong>
                                </p>
                                <p class="mb-4">
                                    Furthermore, upon submission of the survey, you will be issued with an autogenerated number. Please keep this number, on April 7, 2025, the numbers will be drawn and published with instructions on how to claim your gift certificate. This survey is not collecting self identifying information, the onus will be on you to follow the instructions to claim your prize.
                                </p>
                                <p class="mb-4">
                                    It is recognized that you may not directly benefit from this research, however, we hope that your participation in the study will (i) result in greater understanding of how EI impacts the career success of a professional accountant, (ii) help professional accountants assess their EI strengths, and take steps to address gaps (iii) help shape the future CPA curriculum, academic training, and CPA competency mapping.
                                </p>
                                <p class="mb-4">
                                    We believe there are no known risks associated with this research study, however, as with any online related activity, the risk of a breach of confidentiality is always possible. To the best of our ability your answers in this study will remain confidential. We will minimize any risks by collecting non-identifying information, and storing raw data in a secure environment and password restricted computer.
                                </p>
                                <p class="font-bold mb-4">
                                    By clicking "I agree" below you are indicating that you are at least 18 years old, have read and understood this consent form and agree to participate in this research study. Please print a copy of this page for your records.
                                </p>
                            </div>

                            <div class="mt-6 flex items-start space-x-2">
                                <div class="mt-1">
                                    <input type="checkbox" wire:model="consentAgreed"
                                           class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="text-gray-700 font-medium inline-block mb-1 cursor-pointer" for="consentAgreed">
                                        I agree to participate in this research study
                                    </label>
                                    <div class="mt-2">
                                        <button type="button" wire:click="nextStep"
                                                class="mr-2 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                                            I Agree
                                        </button>
                                        <button type="button" onclick="window.history.back()"
                                                class="px-6 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                            I Don't Agree
                                        </button>
                                    </div>
                                    @error('consentAgreed')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Demographics section (moved from step 2) -->
                    @elseif($currentStep == 3)
                        <!-- Demographics Section - now its own step -->
                        <!-- Demographics Section -->
                        <div class="bg-white rounded-xl border border-blue-100 shadow-lg p-6 space-y-8 max-w-4xl mx-auto">
                            <h2 class="text-2xl font-bold text-gray-900 font-serif border-l-4 border-blue-600 pl-4 ml-2">
                                Biographical and Employment Data
                            </h2>
                            <p class="text-gray-600 px-4 bg-blue-50 p-4 rounded-lg">
                                Please provide the following information to help us understand the
                                demographic makeup and employment profile of CPAs. This information is
                                essential to the study, and we appreciate your responses.
                            </p>

                            <!-- Personal Information Section -->
{{--                            <div class="md:col-span-2 bg-gradient-to-r from-blue-600 to-blue-700 p-4 rounded-lg shadow-sm">--}}
{{--                                <h3 class="font-semibold text-white text-lg">Personal Information</h3>--}}
{{--                            </div>--}}

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-2">
                                <!-- Birth Year -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please indicate the year you were born. *
                                    </label>
                                    <input type="number" wire:model="demographicData.birth_year"
                                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300"
                                           placeholder="Use numbers only (e.g 1956)">
                                </div>

                                <!-- Gender -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please select the gender you are most comfortable disclosing. *
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.gender" value="male" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Male</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.gender" value="female" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Female</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.gender" value="other" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Other</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.gender" value="not_disclosed" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Prefer not to disclose</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Languages -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please select the languages you are fluent in. *
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.languages" value="english" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">English</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.languages" value="french" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">French</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.languages" value="indigenous" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Canadian Indigenous languages</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.languages" value="other" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Other</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Professional Background Section -->
{{--                                <div class="md:col-span-2 bg-gradient-to-r from-blue-600 to-blue-700 p-4 rounded-lg shadow-sm mt-6">--}}
{{--                                    <h3 class="font-semibold text-white text-lg">Professional Background</h3>--}}
{{--                                </div>--}}

                                <!-- Legacy Designation -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please indicate the legacy designation that you hold. *
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.legacy_designation" value="CGA" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">CGA</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.legacy_designation" value="CA" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">CA</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.legacy_designation" value="CMA" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">CMA</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.legacy_designation" value="other" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Other</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="checkbox" wire:model="demographicData.legacy_designation" value="none" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">None</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Provincial CPA Body -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please name the Primary Provincial CPA body you are a member of. *
                                    </label>
                                    <select wire:model="demographicData.provincial_cpa_body"
                                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300 bg-select-chevron appearance-none">
                                        <option value="">Select primary body</option>
                                        <option value="CPA Alberta">CPA Alberta</option>
                                        <option value="CPA British Columbia">CPA British Columbia</option>
                                        <option value="CPA Manitoba">CPA Manitoba</option>
                                        <option value="CPA New Brunswick">CPA New Brunswick</option>
                                        <option value="CPA Newfoundland and Labrador">CPA Newfoundland and Labrador</option>
                                        <option value="CPA Nova Scotia">CPA Nova Scotia</option>
                                        <option value="CPA Ontario">CPA Ontario</option>
                                        <option value="CPA Prince Edward Island">CPA Prince Edward Island</option>
                                        <option value="CPA Quebec">CPA Quebec</option>
                                        <option value="CPA Saskatchewan">CPA Saskatchewan</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <!-- Years Since Designation -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please indicate the years since you obtained your designation (including legacy designations). *
                                    </label>
                                    <input type="number" wire:model="demographicData.years_designation"
                                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300"
                                           placeholder="Use numbers only">
                                </div>

                                <!-- Employment Information Section -->
{{--                                <div class="md:col-span-2 bg-gradient-to-r from-blue-600 to-blue-700 p-4 rounded-lg shadow-sm mt-6">--}}
{{--                                    <h3 class="font-semibold text-white text-lg">Employment Information</h3>--}}
{{--                                </div>--}}

                                <!-- Industry -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please indicate the type of industry that best fits where you work? If you are retired or unemployed, indicate the industry at your retirement or your last employer? *
                                    </label>
                                    <select wire:model="demographicData.industry"
                                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300 bg-select-chevron appearance-none">
                                        <option value="">Select industry</option>
                                        <option value="Public Practice">Public Practice</option>
                                        <option value="Private Corporation">Private Corporation</option>
                                        <option value="Publicly traded Corporation">Publicly traded Corporation</option>
                                        <option value="Education">Education</option>
                                        <option value="Government">Government</option>
                                        <option value="Not-for-profit">Not For Profit</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <!-- Current Position -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please indicate the option below that best describes your current position or your most recent position before retirement. *
                                    </label>
                                    <select wire:model="demographicData.current_position"
                                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300 bg-select-chevron appearance-none">
                                        <option value="">Select position</option>
                                        <option value="Sole Proprietor">Sole Proprietor of an accounting firm</option>
                                        <option value="Partner">Partner of an accounting firm</option>
                                        <option value="Owner">Owner of a non-accounting organisation</option>
                                        <option value="Employee Accounting">Employee of an accounting firm</option>
                                        <option value="Employee Non-Accounting">Employee of a non accounting organisation</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <!-- Number of Staff -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please indicate the number of staff (including yourself) in the organization you work for. If you are retired or unemployed, state position on retirement or at your last employer. *
                                    </label>
                                    <input type="number" wire:model="demographicData.number_staff"
                                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300"
                                           placeholder="Use numbers only">
                                </div>

                                <!-- Nature of Work -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please indicate the option that best suits the nature of your work. *
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.work_nature" value="Full-time" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Full-time</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.work_nature" value="Part-time" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Part-time</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.work_nature" value="Contract" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Contract</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.work_nature" value="Unemployed" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Unemployed</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                            <input type="radio" wire:model="demographicData.work_nature" value="Retired" class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Retired</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Career Success Indicators Section -->
{{--                                <div class="md:col-span-2 bg-gradient-to-r from-blue-600 to-blue-700 p-4 rounded-lg shadow-sm mt-6">--}}
{{--                                    <h3 class="font-semibold text-white text-lg">Career Success Indicators</h3>--}}
{{--                                </div>--}}

                                <!-- Yearly Compensation -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        What is your yearly compensation (before taxes, deductions and benefits). If you do not get compensation, please indicate zero. *
                                    </label>
                                    <input type="number" wire:model="demographicData.yearly_compensation"
                                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300"
                                           placeholder="Use numbers only">
                                </div>

                                <!-- Job Title -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        What is your current job title or if you are retired or unemployed, state your position before retirement or unemployment? *
                                    </label>
                                    <select wire:model="demographicData.job_title"
                                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300 bg-select-chevron appearance-none">
                                        <option value="">Select job title</option>
                                        <option value="Sole Practitioner">Sole Practitioner</option>
                                        <option value="Partner">Partner or Principle in public accounting</option>
                                        <option value="Junior">Junior or Entry Level</option>
                                        <option value="Intermediate">Intermediate with no supervision</option>
                                        <option value="Manager">Manager or Supervisor</option>
                                        <option value="Senior Manager">Senior Manager</option>
                                        <option value="Director">Director or VP</option>
                                        <option value="Instructor">Instructor, lecturer, or Professor</option>
                                        <option value="President">President, CEO, or owner of non-public accounting organisation</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <!-- Number Overseen -->
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Please indicate the number of individuals you oversee in your current position or oversaw before retirement or being unemployed. *
                                    </label>
                                    <input type="number" wire:model="demographicData.number_overseen"
                                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300"
                                           placeholder="Use numbers only">
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Career Satisfaction Questions (now its own step) -->
                    @elseif($currentStep == 4)
                        <div class="space-y-6 mt-4">
                            <!-- Career Satisfaction Questions Section -->
                            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                                <h3 class="text-xl font-bold text-blue-700 mb-3">Career Satisfaction</h3>
                                <p class="text-gray-700 leading-relaxed">
                                    The section below is based on the Perceived Career Satisfaction Scale (Greenhaus, Parasuraman, & Wormley, 1990). Please indicate the extent to which the following statements apply to your situation.
                                </p>
                            </div>
                            <!-- Career Satisfaction Questions -->
                            @foreach($careerSatisfactionQuestions as $index => $question)
                                @if($question['question_type']['slug'] === 'likert-scale')
                                    <!-- Question card with improved mobile view -->
                                    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm hover:border-blue-200 transition-colors duration-200">
                                        <div class="flex items-start mb-4">
                                            <!-- Question Number -->
                                            <div class="flex-shrink-0 bg-blue-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                                {{ $index + 1 }}
                                            </div>
                                            <!-- Question Text -->
                                            <p class="text-base font-medium text-gray-900">
                                                {{ $question['question_text'] }}
                                            </p>
                                        </div>

                                        <!-- Desktop view (horizontal) -->
                                        <div class="hidden md:grid md:grid-cols-5 gap-2 ml-11">
                                            @foreach($question['options'] as $option)
                                                <label class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition-colors duration-200">
                                                    <input type="radio"
                                                           wire:model="answers.{{ $question['id'] }}.selected_option"
                                                           value="{{ $option['id'] }}"
                                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                                    <span class="mt-2 text-sm text-gray-700 text-center">
                                    {{ $option['option_text'] }}
                                </span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <!-- Mobile view (vertical) -->
                                        <div class="md:hidden space-y-2 ml-11">
                                            @foreach($question['options'] as $option)
                                                <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition-colors duration-200">
                                                    <input type="radio"
                                                           wire:model="answers.{{ $question['id'] }}.selected_option"
                                                           value="{{ $option['id'] }}"
                                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 mr-3">
                                                    <span class="text-sm text-gray-700">
                                    {{ $option['option_text'] }}
                                </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Step 5: EI Questions (now its own step) -->
                        <!-- Step 5: EI Questions (now its own step) -->
                    @elseif($currentStep == 5)
                        <div class="space-y-6 mt-4">
                            <!-- EI Questions Section -->
                            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                                <div class="mt-6">
                                    <h2 class="text-xl font-bold text-gray-800 mb-3">EI and Career Success of CPAs</h2>
                                    <p class="text-gray-700 mb-4">
                                        This section captures your EI scores. The scores are assessed using the Genos Emotional Intelligence Instrument which measures EI as exhibited in work-related situations. The section and questions are replicated from the Genos EI self-assessment Instrument, Copyright © Genos 2014.
                                    </p>
                                    <p class="text-gray-700 mb-4">
                                        Please read the instructions carefully and remember to complete all questionnaire questions so you can get your EI score.<br> The Genos EI Self Assessment has been designed to measure how often you believe you demonstrate emotionally intelligent behaviours at work.
                                    </p>
                                    <p class="text-gray-700 mb-4">
                                        There are no right or wrong answers. However, it is essential that your responses truly reflect your beliefs regarding how often you demonstrate the behaviour in question. You should not answer in a way that you think sounds good or acceptable. In general try not to spend too long thinking about responses. Most often the first answer that occurs to you is the most accurate. However, do not rush your responses or respond without giving due consideration to each statement.
                                    </p>

                                    <div class="mt-5 bg-gray-50 p-4 rounded-lg">
                                        <p class="font-semibold text-gray-800 mb-2">Below is an example.</p>
                                        <p class="text-gray-700 mb-3"><strong>Q. I display appropriate emotional responses in difficult situations.</strong></p>
                                        <p class="text-gray-700 mb-3">
                                            You are required to indicate on the response scale how often you believe you demonstrate the behaviour in question. There are five possible responses to each statement (shown below). You are required to circle the number that corresponds to your answer where...
                                        </p>
                                        <div class="flex justify-between text-gray-800 font-medium mt-4 px-2">
                                            <span>1 = Almost Never</span>
                                            <span>2 = Seldom</span>
                                            <span>3 = Sometimes</span>
                                            <span>4 = Usually</span>
                                            <span>5 = Almost Always</span>
                                        </div>
                                    </div>

                                    <p class="text-gray-700 mt-5 mb-4">
                                        When considering a response it is important not to think of the way you behaved in any one situation, rather your responses should be based on your typical behaviour. Also, some of the questions may not give all the information you would like to receive. If this is the case, please choose a response that seems most likely.
                                    </p>
                                    <p class="text-gray-700">
                                        There is no time limit; however it should take between 15-25 minutes to complete.
                                    </p>
                                    <p class="text-gray-700">
                                        Please note that, although you are free to skip any question, the validity of the research findings and the accuracy of your EI score depends on the completeness of this survey.
                                    </p>
                                </div>
                            </div>

                            @foreach($eiQuestions as $index => $question)
                                @if($question['question_type']['slug'] === 'likert-scale')
                                    <!-- Question card with improved mobile view -->
                                    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm hover:border-blue-200 transition-colors duration-200">
                                        <div class="flex items-start mb-4">
                                            <!-- Question Number -->
                                            <div class="flex-shrink-0 bg-blue-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                                {{ $index + 1 }}
                                            </div>
                                            <!-- Question Text -->
                                            <p class="text-base font-medium text-gray-900">
                                                {{ $question['question_text'] }}
                                            </p>
                                        </div>
                                        <!-- Desktop view (horizontal) -->
                                        <div class="hidden md:grid md:grid-cols-5 gap-2 ml-11">
                                            @foreach($question['options'] as $option)
                                                <label class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition-colors duration-200">
                                                    <input type="radio"
                                                           wire:model="answers.{{ $question['id'] }}.selected_option"
                                                           value="{{ $option['id'] }}"
                                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                                    <span class="mt-2 text-sm text-gray-700 text-center">
                                    {{ $option['option_text'] }}
                                </span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <!-- Mobile view (vertical) -->
                                        <div class="md:hidden space-y-2 ml-11">
                                            @foreach($question['options'] as $option)
                                                <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition-colors duration-200">
                                                    <input type="radio"
                                                           wire:model="answers.{{ $question['id'] }}.selected_option"
                                                           value="{{ $option['id'] }}"
                                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 mr-3">
                                                    <span class="text-sm text-gray-700">
                                    {{ $option['option_text'] }}
                                </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Add section break every 7 questions -->
                                    @if(($index + 1) % 7 == 0 && ($index + 1) < count($eiQuestions))
                                        <div class="border-t border-gray-200 my-8 pt-2 text-center">
                        <span class="inline-block px-4 py-1 bg-blue-50 rounded-full text-sm text-blue-600 font-medium">
                            Section {{ floor(($index + 1) / 7) + 1 }}
                        </span>
                                        </div>
                                    @endif
                                @endif
                            @endforeach

                            <!-- Career Challenges Question (after Q70) -->
                            <div class="border-t border-gray-200 my-8 pt-2 text-center">
            <span class="inline-block px-4 py-1 bg-blue-50 rounded-full text-sm text-blue-600 font-medium">
                Final Section
            </span>
                            </div>

                            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm hover:border-blue-200 transition-colors duration-200">
                                <div class="flex items-start mb-4">
                                    <div class="flex-shrink-0 bg-blue-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                        71
                                    </div>
                                    <p class="text-base font-medium text-gray-900">
                                        Please use the space below to capture your thoughts about the challenges and opportunities you see in your career as a professional accountant.
                                    </p>
                                </div>
                                <div class="ml-11">
                <textarea wire:model="careerChallengesText" rows="6"
                          class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all duration-300 placeholder-gray-400 hover:border-blue-300"
                          placeholder="Share your thoughts here..."></textarea>
                                </div>
                            </div>

                            <!-- Consent Confirmation -->
                            <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <p class="text-gray-700 text-center">
                                    Thank you for your time. The submission of the questionnaire serves as a form of implied consent.
                                    It also confirms that you read and understood the consent information.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="mt-12 flex justify-between">
                        @if($currentStep > 1)
                            <button type="button" wire:click="previousStep"
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-full font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-all duration-200">
                                ← Back
                            </button>
                        @else
                            <div></div>
                        @endif

                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-75 cursor-wait"
                                class="inline-flex items-center px-8 py-3 border border-transparent rounded-full font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg transform transition hover:scale-105 duration-200">
                            <span wire:loading.remove wire:target="nextStep">
                                {{ $currentStep < $totalSteps ? 'Continue →' : 'Submit Survey' }}
                            </span>
                            <span wire:loading wire:target="nextStep" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ $currentStep < $totalSteps ? 'Processing...' : 'Calculating Results...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        window.addEventListener('scrollToTop', event => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg animate-slideIn';
                toast.textContent = '✓ Copied to clipboard!';
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 2000);
            });
        }
    </script>

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
        .shadow-inner {
            box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.05);
        }
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }
        /* Custom dropdown indicator */
        /*.bg-chevron {*/
        /*    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");*/
        /*    background-position: right 0.5rem center;*/
        /*    background-repeat: no-repeat;*/
        /*    background-size: 1.5em 1.5em;*/
        /*}*/

        .bg-select-chevron {
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25em;
        }
    </style>
</div>

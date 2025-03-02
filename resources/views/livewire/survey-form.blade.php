<div class="py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    @if($completionCode)
        <!-- Check if the person is not a CPA member -->
        @if($demographicData['is_cpa_member'] === 'no')
            <!-- Non-CPA Member Message -->
            <div class="bg-white shadow-xl rounded-2xl p-8 text-center transform transition-all duration-300 hover:shadow-2xl">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 mb-6">
                    <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h2 class="mt-4 text-3xl font-bold text-gray-900 font-serif">Thank You</h2>
                <p class="mt-2 text-lg text-gray-600">This survey is specifically for CPA Canada members.</p>
                <p class="mt-4 text-gray-600">Thank you for your interest in participating. We appreciate your time.</p>

                <div class="mt-10">
                    <a href="{{ route('surveys.index') }}"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                        </svg>
                        Return to Surveys
                    </a>
                </div>
            </div>
        @else
            <!-- Success Card for CPA Members -->
            <!-- Success Card with Scores -->
            <div class="bg-white shadow-xl rounded-2xl p-8 text-center transform transition-all duration-300 hover:shadow-2xl">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6 animate-bounce">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="mt-4 text-3xl font-bold text-gray-900 font-serif">Thank You! 🎉</h2>
                <p class="mt-2 text-lg text-gray-600">Your responses have been successfully recorded</p>

                @if($showScores)
                    <!-- Score Results Section -->
                    <div class="mt-8 p-6 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl border border-blue-100">
                        <h3 class="text-xl font-bold text-blue-700 mb-6">Your Emotional Intelligence Profile</h3>

                        <!-- Total Score -->
                        <div class="bg-white rounded-lg p-4 shadow-md mb-6 flex items-center justify-between">
                            <div class="text-left">
                                <h4 class="font-bold text-lg text-gray-800">Overall EI Score</h4>
                                <p class="text-gray-500 text-sm">Based on your responses to all questions</p>
                            </div>
                            <div class="text-right">
                    <span class="text-3xl font-bold {{ $totalScore > 75 ? 'text-green-600' : ($totalScore > 50 ? 'text-blue-600' : 'text-gray-600') }}">
                        {{ $totalScore }}
                    </span>
                                <span class="text-sm text-gray-500">/100</span>
                            </div>
                        </div>

                        <!-- EI Category Scores -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Self Awareness -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <h4 class="font-bold text-gray-800 mb-1">Self Awareness</h4>
                                <div class="w-full bg-gray-200 rounded-full h-4 mb-1">
                                    <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $eiScores['self_awareness']['percentage'] }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Score: {{ $eiScores['self_awareness']['raw'] }}</span>
                                    <span>{{ $eiScores['self_awareness']['percentage'] }}%</span>
                                </div>
                            </div>

                            <!-- Self Regulation -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <h4 class="font-bold text-gray-800 mb-1">Self Regulation</h4>
                                <div class="w-full bg-gray-200 rounded-full h-4 mb-1">
                                    <div class="bg-indigo-600 h-4 rounded-full" style="width: {{ $eiScores['self_regulation']['percentage'] }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Score: {{ $eiScores['self_regulation']['raw'] }}</span>
                                    <span>{{ $eiScores['self_regulation']['percentage'] }}%</span>
                                </div>
                            </div>

                            <!-- Motivation -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <h4 class="font-bold text-gray-800 mb-1">Motivation</h4>
                                <div class="w-full bg-gray-200 rounded-full h-4 mb-1">
                                    <div class="bg-purple-600 h-4 rounded-full" style="width: {{ $eiScores['motivation']['percentage'] }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Score: {{ $eiScores['motivation']['raw'] }}</span>
                                    <span>{{ $eiScores['motivation']['percentage'] }}%</span>
                                </div>
                            </div>

                            <!-- Empathy -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <h4 class="font-bold text-gray-800 mb-1">Empathy</h4>
                                <div class="w-full bg-gray-200 rounded-full h-4 mb-1">
                                    <div class="bg-green-600 h-4 rounded-full" style="width: {{ $eiScores['empathy']['percentage'] }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Score: {{ $eiScores['empathy']['raw'] }}</span>
                                    <span>{{ $eiScores['empathy']['percentage'] }}%</span>
                                </div>
                            </div>

                            <!-- Social Skills -->
                            <div class="bg-white rounded-lg p-4 shadow-sm md:col-span-2">
                                <h4 class="font-bold text-gray-800 mb-1">Social Skills</h4>
                                <div class="w-full bg-gray-200 rounded-full h-4 mb-1">
                                    <div class="bg-teal-600 h-4 rounded-full" style="width: {{ $eiScores['social_skills']['percentage'] }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Score: {{ $eiScores['social_skills']['raw'] }}</span>
                                    <span>{{ $eiScores['social_skills']['percentage'] }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600 mt-4 bg-blue-50 p-4 rounded-lg">
                            <p class="font-medium mb-2">What does this mean?</p>
                            <p>Your EI profile shows your emotional intelligence strengths and areas for potential development. Higher scores indicate stronger abilities in each area.</p>
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
                        ✨ Keep this code safe! You'll need it to claim rewards if selected as a winner.
                    </p>
                </div>

                <div class="mt-10">
                    <a href="{{ route('surveys.index') }}"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                        </svg>
                        Return to Surveys
                    </a>
                </div>
            </div>
        @endif

    @else
        <!-- Survey Container -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h1 class="text-3xl font-bold text-gray-900 font-serif">{{ $survey->title }}</h1>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    ✨ Fully complete all questions to help shape the CPA profession and get a chance to win:
                    <span class="block mt-2 text-blue-700 font-medium">
                        $100 Visa gift card • EI score report • Enhanced work appreciation insights
                    </span>
                </p>
            </div>

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

            <div class="p-8">
                <form wire:submit.prevent="nextStep">
                    <!-- Step 1: Consent Form -->
                    @if($currentStep == 1)
                        <div class="mb-8 p-6 bg-white rounded-xl border border-blue-100 shadow-sm">
                            <h2 class="text-2xl font-bold text-gray-900 font-serif mb-4">Consent Form</h2>
                            <div class="prose prose-blue max-w-none">
                                <p class="text-gray-700 leading-relaxed">
                                    Please read the information below before proceeding.
                                </p>
                                <p class="text-gray-700 mt-4">
                                    Furthermore, upon submission of the survey, you will be issued with an autogenerated number.
                                    Please keep this number. On April 2, 2025, winning numbers will be drawn and published with instructions on how to claim the Visa card.
                                    This survey is not collecting self-identifying information; the onus will be on you to follow the instructions to claim your prize.
                                </p>
                                <p class="text-gray-700 mt-4">
                                    We believe there are no known risks associated with this research study. However, as with any online-related activity,
                                    the risk of a breach of confidentiality is always possible. To the best of our ability, your answers in this study will remain confidential.
                                    We will minimize any risks by collecting non-identifying information and storing raw data in a secure environment and password-restricted computer.
                                </p>
                                <p class="text-gray-700 mt-4">
                                    By clicking "I Agree" below, you are indicating that you are at least 18 years old, have read and understood this consent form,
                                    and agree to participate in this research study.
                                </p>
                            </div>

                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <label class="flex items-start space-x-3">
                                    <input type="checkbox" wire:model="consentAgreed"
                                           class="mt-1 h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-gray-700 font-medium">
                                        I agree to the terms and consent to participate
                                    </span>
                                </label>
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

                        <!-- Step 2: CPA Membership and Demographics -->
                    @elseif($currentStep == 2)
                        <div class="mb-8 p-6 bg-white rounded-xl border border-blue-100 shadow-sm">
                            <h2 class="text-2xl font-bold text-gray-900 font-serif border-l-4 border-blue-600 pl-4 mb-6">
                                EI and Career Success of CPAs
                            </h2>

                            <div class="mt-8 space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">
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

                        @if($demographicData['is_cpa_member'] === 'yes')
                            <div class="bg-white rounded-xl border border-blue-100 shadow-lg p-8 max-w-5xl mx-auto">
                                <!-- Header Section -->
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 font-serif border-l-4 border-blue-600 pl-4">
                                        About You
                                    </h2>
                                    <p class="mt-2 text-gray-500 text-sm pl-4 ml-4">
                                        Please provide the following information to help us understand the demographic makeup of CPA members.
                                    </p>
                                </div>

                                <!-- Form Grid -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-6">
                                    <!-- First Column -->
                                    <div class="space-y-6">
                                        <!-- Birth Year -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Please indicate the year you were born.
                                            </label>
                                            <input type="number" wire:model="demographicData.birth_year"
                                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 placeholder-gray-400"
                                                   placeholder="e.g. 1995">
                                            @error('demographicData.birth_year')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Provincial CPA Body -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Please name the Primary Provincial CPA body you are a member of.
                                            </label>
                                            <input type="text" wire:model="demographicData.provincial_cpa_body"
                                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 placeholder-gray-400"
                                                   placeholder="Your answer">
                                            @error('demographicData.provincial_cpa_body')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Years Since Designation -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Please indicate the years since you obtained your designation.
                                            </label>
                                            <input type="number" wire:model="demographicData.years_designation"
                                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 placeholder-gray-400"
                                                   placeholder="Number only">
                                            @error('demographicData.years_designation')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Industry -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Please indicate the type of industry that best fits where you work.
                                            </label>
                                            <div class="relative">
                                                <select wire:model="demographicData.industry"
                                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 appearance-none">
                                                    <option value="">Select industry</option>
                                                    <option value="Public Practice">Public Practice</option>
                                                    <option value="Private Corporation">Private Corporation</option>
                                                    <option value="Publicly traded Corporation">Publicly traded Corporation</option>
                                                    <option value="Education">Education</option>
                                                    <option value="Government">Government</option>
                                                    <option value="Not-for-profit">Not-for-profit</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('demographicData.industry')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Current Position -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Please indicate your current or most recent position.
                                            </label>
                                            <div class="relative">
                                                <select wire:model="demographicData.current_position"
                                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 appearance-none">
                                                    <option value="">Select position</option>
                                                    <option value="Sole Proprietor">Sole Proprietor of an accounting firm</option>
                                                    <option value="Partner">Partner of an accounting firm</option>
                                                    <option value="Owner">Owner of a non-accounting organization</option>
                                                    <option value="Employee Accounting">Employee of an accounting firm</option>
                                                    <option value="Employee Non-Accounting">Employee of a non-accounting organization</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('demographicData.current_position')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Second Column -->
                                    <div class="space-y-6">
                                        <!-- Gender -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Please select the gender you are most comfortable disclosing.
                                            </label>
                                            <div class="relative">
                                                <select wire:model="demographicData.gender"
                                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 appearance-none">
                                                    <option value="">Select gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                    <option value="other">Other</option>
                                                    <option value="not_disclosed">Prefer not to disclose</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('demographicData.gender')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Number of Staff -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Number of staff in your organization (including yourself).
                                            </label>
                                            <input type="number" wire:model="demographicData.number_staff"
                                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 placeholder-gray-400"
                                                   placeholder="Number only">
                                            @error('demographicData.number_staff')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <!-- Yearly Compensation -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Yearly compensation (before taxes/deductions).
                                            </label>
                                            <input type="number" wire:model="demographicData.yearly_compensation"
                                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 placeholder-gray-400"
                                                   placeholder="Number only">
                                            @error('demographicData.yearly_compensation')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Job Title -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Current or most recent job title/level.
                                            </label>
                                            <div class="relative">
                                                <select wire:model="demographicData.job_title"
                                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 appearance-none">
                                                    <option value="">Select job title</option>
                                                    <option value="Sole Practitioner">Sole Practitioner</option>
                                                    <option value="Partner">Partner/Principle in public accounting</option>
                                                    <option value="Junior">Junior or entry level</option>
                                                    <option value="Intermediate">Intermediate with no supervision</option>
                                                    <option value="Manager">Manager or Supervisor</option>
                                                    <option value="Senior Manager">Senior Manager</option>
                                                    <option value="Director">Director or VP</option>
                                                    <option value="Instructor">Instructor, lecturer, or professor</option>
                                                    <option value="President">President, CEO, or owner</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('demographicData.job_title')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Number Overseen -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Number of individuals you oversee/overseen.
                                            </label>
                                            <input type="number" wire:model="demographicData.number_overseen"
                                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition-all duration-150 placeholder-gray-400"
                                                   placeholder="Number only">
                                            @error('demographicData.number_overseen')
                                            <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Full Width Fields -->
                                <div class="mt-8 space-y-8">
                                    <!-- Languages -->
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Please select the languages you are fluent in.
                                        </label>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.languages" value="english"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">English</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.languages" value="french"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">French</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.languages" value="indigenous"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">Canadian Indigenous</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.languages" value="other"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">Other</span>
                                            </label>
                                        </div>
                                        @error('demographicData.languages')
                                        <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Legacy Designation -->
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Please indicate the legacy designation(s) that you hold.
                                        </label>
                                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.legacy_designation" value="CGA"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">CGA</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.legacy_designation" value="CA"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">CA</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.legacy_designation" value="CMA"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">CMA</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.legacy_designation" value="other"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">Other</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" wire:model="demographicData.legacy_designation" value="none"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">None</span>
                                            </label>
                                        </div>
                                        @error('demographicData.legacy_designation')
                                        <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Nature of Work -->
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Please indicate the nature of your work.
                                        </label>
                                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="radio" wire:model="demographicData.work_nature" value="Full-time"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">Full-time</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="radio" wire:model="demographicData.work_nature" value="Part-time"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">Part-time</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="radio" wire:model="demographicData.work_nature" value="Contract"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">Contract</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="radio" wire:model="demographicData.work_nature" value="Unemployed"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">Unemployed</span>
                                            </label>
                                            <label class="flex items-center p-3 rounded-lg border-2 border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 transition-all duration-150 cursor-pointer">
                                                <input type="radio" wire:model="demographicData.work_nature" value="Retired"
                                                       class="h-5 w-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500 mr-3">
                                                <span class="text-gray-700">Retired</span>
                                            </label>
                                        </div>
                                        @error('demographicData.work_nature')
                                        <p class="text-sm text-red-600 italic mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Step 3: Career Satisfaction Questions -->
                    @elseif($currentStep == 3)
                        <div class="space-y-8">
                            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                                <p class="text-gray-700 leading-relaxed">
                                    📊 This section uses the Perceived Career Satisfaction Scale (Greenhaus et al., 1990).
                                    Please rate how much each statement applies to you:
                                </p>
                            </div>
                            <div class="overflow-x-auto pb-4">
                                <div class="min-w-[800px] md:min-w-full space-y-6">
                                    @foreach($careerSatisfactionQuestions as $index => $question)
                                        @if($question['question_type']['slug'] === 'likert-scale')
                                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:border-blue-200 transition-colors duration-200">
                                                <div class="flex items-start mb-4">
                                                    <!-- Question Number -->
                                                    <div class="flex-shrink-0 bg-blue-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <!-- Question Text -->
                                                    <p class="text-base font-medium text-gray-900">
                                                        {{ $question['question_text'] }}
                                                        <span class="text-red-500 ml-1">*</span>
                                                    </p>
                                                </div>
                                                <div class="grid grid-cols-5 gap-2 ml-11">
                                                    @foreach($question['options'] as $option)
                                                        <label class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition-colors duration-200">
                                                            <input type="radio"
                                                                   wire:model="answers.{{ $question['id'] }}.selected_option"
                                                                   value="{{ $option['id'] }}"
                                                                   required
                                                                   class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                                            <span class="mt-2 text-sm text-gray-700 text-center">
                                                            {{ $option['option_text'] }}
                                                        </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error("answers.{$question['id']}.selected_option")
                                                <p class="mt-2 text-sm text-red-600 flex items-center ml-11">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                                @enderror
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <!-- Open-ended question -->
                            <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm">
                                <p class="text-lg font-medium text-gray-900 mb-4">
                                    Please use the space below to capture your thoughts about the challenges and opportunities you see in your career as a professional accountant.
                                </p>
                                <textarea wire:model="careerChallengesText" rows="5"
                                          class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm"
                                          placeholder="Your answer">
                                </textarea>
                            </div>
                        </div>
                    @endif

                    <div class="mt-12 flex justify-between">
                        @if($currentStep > 1)
                            <button type="button" wire:click="previousStep"
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-full font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-all duration-200">
                                ← Back
                            </button>
                        @else
                            <div></div>
                        @endif
                        <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent rounded-full font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg transform transition hover:scale-105 duration-200">
                            {{ $currentStep < $totalSteps ? 'Continue →' : 'Submit Survey' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
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
    </style>
</div>

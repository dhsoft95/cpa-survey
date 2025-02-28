<!-- resources/views/livewire/survey-form.blade.php -->
<div class="py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    @if($completionCode)
        <div class="bg-white shadow-sm rounded-lg p-8 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="mt-4 text-2xl font-bold text-gray-900">Thank You!</h2>
            <p class="mt-2 text-gray-600">Your responses have been recorded.</p>

            <div class="mt-6 p-4 bg-gray-50 rounded-md flex flex-col items-center">
                <p class="text-sm text-gray-600 mb-1">Your completion code:</p>
                <div class="flex items-center space-x-2">
                    <span class="text-2xl font-bold text-blue-700 tracking-wide">{{ $completionCode }}</span>
                    <button onclick="copyToClipboard('{{ $completionCode }}')" class="text-gray-500 hover:text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                    </button>
                </div>
                <p class="mt-4 text-sm text-gray-600 text-center">
                    Please save this code. You'll need it to claim any rewards if your response is selected as a winner.
                </p>
            </div>

            <div class="mt-8">
                <a href="{{ route('surveys.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Return to Surveys
                </a>
            </div>
        </div>
    @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-xl font-semibold text-gray-900">{{ $survey->title }}</h1>
                <p class="mt-2 text-gray-600">Fully complete all questions to help shape the CPA profession and get a chance to win a $100 Visa gift card, your EI score, and an enhanced appreciation of work-related situations impacted by EI.</p>
            </div>

            <div class="p-6">
                <div class="mb-6">
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                        </div>
                        <span class="ml-4 text-sm text-gray-600">Step {{ $currentStep }} of {{ $totalSteps }}</span>
                    </div>
                </div>

                <form wire:submit.prevent="nextStep">
                    <!-- Step 1: Consent and Demographics -->
                    @if($currentStep == 1)
                        <div class="mb-6 p-4 bg-gray-50 rounded-md">
                            <p class="mb-4 text-sm text-gray-700">
                                Thank you for your time. The submission of the questionnaire serves as a form of implied consent.
                                It also confirms that you read and understood the consent information.
                            </p>

                            <div class="mb-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="consentAgreed" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">I agree to the terms and consent to participate</span>
                                </label>
                                @error('consentAgreed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Are you a member of CPA Canada? <span class="text-red-500">*</span></label>
                                <div class="space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" wire:model="demographicData.is_cpa_member" value="yes" class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" wire:model="demographicData.is_cpa_member" value="no" class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2">No</span>
                                    </label>
                                </div>
                                @error('demographicData.is_cpa_member')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Only show demographic details if they are a CPA member -->
                        @if($demographicData['is_cpa_member'] === 'yes')
                            <div class="mb-8 p-4 bg-gray-50 rounded-md">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">About You</h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="birth_year" class="block text-sm font-medium text-gray-700 mb-1">Year of Birth <span class="text-red-500">*</span></label>
                                        <input type="number" id="birth_year" wire:model="demographicData.birth_year"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                               placeholder="e.g., 1980">
                                        @error('demographicData.birth_year')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                                        <select id="gender" wire:model="demographicData.gender"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="">Select gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                            <option value="not_disclosed">Prefer not to disclose</option>
                                        </select>
                                        @error('demographicData.gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Languages <span class="text-red-500">*</span></label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="demographicData.languages" value="english"
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">English</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="demographicData.languages" value="french"
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">French</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="demographicData.languages" value="indigenous"
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Canadian Indigenous languages</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="demographicData.languages" value="other"
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Other</span>
                                            </label>
                                        </div>
                                        @error('demographicData.languages')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Provincial CPA Body <span class="text-red-500">*</span></label>
                                        <select id="province" wire:model="demographicData.province"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="">Select province</option>
                                            <option value="AB">CPA Alberta</option>
                                            <option value="BC">CPA British Columbia</option>
                                            <option value="MB">CPA Manitoba</option>
                                            <option value="NB">CPA New Brunswick</option>
                                            <option value="NL">CPA Newfoundland and Labrador</option>
                                            <option value="NS">CPA Nova Scotia</option>
                                            <option value="ON">CPA Ontario</option>
                                            <option value="PE">CPA Prince Edward Island</option>
                                            <option value="QC">CPA Quebec</option>
                                            <option value="SK">CPA Saskatchewan</option>
                                        </select>
                                        @error('demographicData.province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="legacy_designation" class="block text-sm font-medium text-gray-700 mb-1">Legacy Designation <span class="text-red-500">*</span></label>
                                        <select id="legacy_designation" wire:model="demographicData.legacy_designation"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="">Select designation</option>
                                            <option value="CGA">CGA</option>
                                            <option value="CA">CA</option>
                                            <option value="CMA">CMA</option>
                                            <option value="other">Other</option>
                                            <option value="none">None</option>
                                        </select>
                                        @error('demographicData.legacy_designation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="years_designation" class="block text-sm font-medium text-gray-700 mb-1">Years Since Designation <span class="text-red-500">*</span></label>
                                        <input type="number" id="years_designation" wire:model="demographicData.years_designation"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                               placeholder="e.g., 5">
                                        @error('demographicData.years_designation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">Industry <span class="text-red-500">*</span></label>
                                        <select id="industry" wire:model="demographicData.industry"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="">Select industry</option>
                                            <option value="Public Practice">Public Practice</option>
                                            <option value="Private Corporation">Private Corporation</option>
                                            <option value="Publicly traded Corporation">Publicly traded Corporation</option>
                                            <option value="Education">Education</option>
                                            <option value="Government">Government</option>
                                            <option value="Not For Profit">Not For Profit</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        @error('demographicData.industry')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Step 2: Career Satisfaction Questions -->
                    @elseif($currentStep == 2)
                        <div class="mb-8">
                            <div class="mb-6">
                                <div class="p-4 bg-gray-50 rounded-md">
                                    <p class="font-medium text-gray-900">
                                        The section below is based on the Perceived Career Satisfaction Scale (Greenhaus, Parasuraman, & Wormley, 1990). Please indicate the extent to which the following statements apply to your situation.
                                    </p>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Strongly Disagree</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disagree to some extent</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Uncertain</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Agree to some extent</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Strongly agree</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($careerSatisfactionQuestions as $index => $question)
                                        @if(isset($question['question_type']) && isset($question['question_type']['slug']) && $question['question_type']['slug'] === 'likert-scale')
                                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                <td class="px-4 py-4 text-sm text-gray-900">
                                                    {{ $question['question_text'] }}
                                                    @if($question['is_required'])
                                                        <span class="text-red-500">*</span>
                                                    @endif
                                                </td>
                                                @foreach($question['options'] as $optionIndex => $option)
                                                    <td class="px-4 py-4 text-center">
                                                        <input
                                                            type="radio"
                                                            wire:model="answers.{{ $question['id'] }}.selected_option"
                                                            value="{{ $option['id'] }}"
                                                            class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                        >
                                                    </td>
                                                @endforeach
                                            </tr>
                                            @if($errors->has("answers.{$question['id']}.selected_option"))
                                                <tr>
                                                    <td colspan="6" class="px-4 py-2 text-sm text-red-600">
                                                        {{ $errors->first("answers.{$question['id']}.selected_option") }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 flex justify-between">
                        @if($currentStep > 1)
                            <button type="button" wire:click="previousStep"
                                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Back
                            </button>
                        @else
                            <div></div>
                        @endif

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ $currentStep < $totalSteps ? 'Continue' : 'Submit' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Completion code copied to clipboard!');
        }, (err) => {
            console.error('Could not copy text: ', err);
        });
    }
</script>

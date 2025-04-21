<div class="winners-container relative">
    <!-- Confetti Effect (Only shows when there are winners) -->
    @if(!$winners->isEmpty())
        <div class="confetti-container fixed inset-0 pointer-events-none z-10" id="confetti-container"></div>
    @endif
    <!-- Header Section with Title and Description -->
    <div class="mb-12 text-center relative">
        <!-- Floating Balloons (Left Side) -->
        <div class="absolute left-0 top-0 transform -translate-x-1/2 hidden md:block">
            <div class="balloon balloon-blue animate-float-slow" style="animation-delay: 0s;"></div>
            <div class="balloon balloon-indigo animate-float-slow" style="animation-delay: 1.5s; margin-left: 20px;"></div>
        </div>
        <!-- Floating Balloons (Right Side) -->
        <div class="absolute right-0 top-0 transform translate-x-1/2 hidden md:block">
            <div class="balloon balloon-purple animate-float-slow" style="animation-delay: 0.7s;"></div>
            <div class="balloon balloon-blue animate-float-slow" style="animation-delay: 2.2s; margin-right: 20px;"></div>
        </div>

        <div class="inline-block bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full p-4 mb-5 shadow-lg relative">
            <!-- Trophy Icon with Sparkle Effect -->
            <svg class="w-14 h-14 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <!-- Sparkles -->
            <div class="absolute -top-1 -right-1 animate-ping-slow">
                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                    <path d="M11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V11a1 1 0 102 0V8.732a2 2 0 000-3.464V4z"></path>
                </svg>
            </div>
            <div class="absolute -bottom-1 -left-1 animate-ping-slow" style="animation-delay: 0.5s;">
                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                    <path d="M11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V11a1 1 0 102 0V8.732a2 2 0 000-3.464V4z"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 relative">
            Survey Prize Winners
            <!-- Celebration Emoji Stars Only if Winners Exist -->
            @if(!$winners->isEmpty())
                <span class="absolute -right-12 top-0 text-2xl animate-bounce-slow">🎉</span>
                <span class="absolute -left-12 top-0 text-2xl animate-bounce-slow" style="animation-delay: 0.3s;">🎊</span>
            @endif
        </h1>

        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Congratulations to our EI CPA Career Success Survey prize winners! Thank you to all participants for contributing to this important research.</p>
    </div>

    <!-- Winners Section -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-10 relative">
        <!-- Section Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 py-6 px-8">
            <h2 class="text-2xl font-bold text-white">Recent Winners</h2>
        </div>

        @if($winners->isEmpty())
            <!-- Empty State -->
            <div class="p-10 text-center">
                <div class="bg-blue-50 rounded-full p-5 w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">No Winners Yet</h3>
                <p class="text-gray-500 text-lg">Prize winners will be announced here after the draw.</p>
            </div>
        @else
            <!-- Winners List Table with Trophy Animation -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Draw Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Winner Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Survey</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($winners as $index => $winner)
                        <tr class="hover:bg-gray-50 winner-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">{{ $winner->updated_at->format('M d, Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $winner->updated_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="winner-code text-lg font-semibold">
                                        {{ $winner->completion_code }}
                                        @if($index === 0)
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Latest
                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <div class="flex items-center text-sm text-indigo-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Score: {{ $winner->total_score }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="text-sm font-medium text-gray-900">{{ $winner->survey->title }}</div>
                                <div class="text-xs text-gray-500">Research Study</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="mr-2 flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="text-green-800 font-medium">${{ number_format(100, 0) }} Gift Card</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="mailto:jenipher@andrews.edu?subject=CPA Survey Prize Claim - {{ $winner->completion_code }}&body=Hello,%0A%0AI am claiming my prize for the CPA Survey.%0A%0AMy completion code is: {{ $winner->completion_code }}%0A%0AThank you!"
                                   class="claim-reward-button">
                                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Claim Reward
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Livewire Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $winners->links() }}
            </div>
        @endif
    </div>

    <!-- How Winners Are Selected Section -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-10">
        <div class="p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">How Winners Are Selected</h3>
            <div class="prose text-gray-600">
                <p>Prize winners are selected through a random draw from all completed survey responses. To be eligible:</p>
                <ul class="list-disc ml-5 space-y-2 mt-2">
                    <li>Participants must complete all required sections of the survey</li>
                    <li>Each participant receives a unique completion code upon finishing the survey</li>
                    <li>
                        If you have a winning number, email
                        <a style="color: #0000cc" href="mailto:jenipher@andrews.edu">jenipher@andrews.edu</a> to claim your prize.</li>
                    <li>Prize draws are conducted monthly during the research period</li>
                </ul>
                <p class="mt-4">For privacy reasons, only winner codes are displayed publicly. Winners will be contacted directly with instructions on how to claim their prize.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center">
        <a href="{{ route('surveys.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Back to Surveys
        </a>
    </div>

    <!-- Tailwind CSS Custom Styles -->
    <style>
        /* Balloon Animations */
        @keyframes float-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Slower ping animation */
        @keyframes ping-slow {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.5; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Slow bounce animation */
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Shimmer effect for trophy badges */
        @keyframes shimmer {
            0% { background-position: -100% 0; }
            100% { background-position: 100% 0; }
        }

        .animate-float-slow {
            animation: float-slow 6s ease-in-out infinite;
        }

        .animate-ping-slow {
            animation: ping-slow 3s ease-in-out infinite;
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s ease-in-out infinite;
        }

        /* Balloon styling */
        .balloon {
            width: 60px;
            height: 70px;
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            border-radius: 50% 50% 50% 50% / 40% 40% 60% 60%;
            position: relative;
            margin-bottom: 20px;
            box-shadow: inset -5px -5px 15px rgba(0,0,0,0.1);
        }

        .balloon:after {
            content: "";
            position: absolute;
            width: 2px;
            height: 30px;
            background: #d1d5db;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .balloon-blue {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        }

        .balloon-indigo {
            background: linear-gradient(135deg, #818cf8 0%, #6366f1 100%);
        }

        .balloon-purple {
            background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
        }

        /* Trophy badge shimmer effect */
        .trophy-badge {
            position: relative;
            overflow: hidden;
        }

        .trophy-badge:after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                rgba(255,255,255,0) 0%,
                rgba(255,255,255,0.3) 50%,
                rgba(255,255,255,0) 100%
            );
            transform: rotate(30deg);
            animation: shimmer 3s infinite;
            background-size: 200% 100%;
            opacity: 0.6;
        }

        /* Prize badge hover effect */
        .prize-badge {
            transition: all 0.3s ease;
        }

        .prize-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Winner row hover effect */
        .winner-row {
            transition: all 0.2s ease;
        }

        .winner-row:hover {
            background-color: #f9fafb;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .claim-button {
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .claim-button:hover {
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }

        .claim-button::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                rgba(99, 102, 241, 0) 0%,
                rgba(99, 102, 241, 0.1) 50%,
                rgba(99, 102, 241, 0) 100%
            );
            transform: rotate(30deg);
            animation: shimmer 3s infinite;
            background-size: 200% 100%;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .claim-button:hover::after {
            opacity: 1;
        }

        /* Winner code styling */
        .winner-code {
            color: #4f46e5;
            letter-spacing: 0.03em;
        }

        /* Claim reward button styling */
        .claim-reward-button {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: #4f46e5;
            background-color: #eef2ff;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .claim-reward-button:hover {
            background-color: #e0e7ff;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .claim-reward-button svg {
            color: #4f46e5;
        }

        /* Winner row hover */
        .winner-row:hover {
            background-color: #f9fafb;
        }
    </style>

    <!-- Confetti JavaScript - Only loads if there are winners -->
    @if(!$winners->isEmpty())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Simple confetti function
                function createConfetti() {
                    const container = document.getElementById('confetti-container');
                    const colors = ['#3b82f6', '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b'];

                    for (let i = 0; i < 100; i++) {
                        const confetti = document.createElement('div');
                        const size = Math.random() * 10 + 5;

                        confetti.style.width = `${size}px`;
                        confetti.style.height = `${size}px`;
                        confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                        confetti.style.position = 'absolute';
                        confetti.style.top = '-10px';
                        confetti.style.left = `${Math.random() * 100}%`;
                        confetti.style.opacity = Math.random() * 0.7 + 0.3;
                        confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                        confetti.style.transform = `rotate(${Math.random() * 360}deg)`;

                        // Animation properties
                        const duration = Math.random() * 3 + 2;
                        const delay = Math.random() * 2;

                        confetti.style.animation = `fall ${duration}s ease-in ${delay}s forwards`;

                        container.appendChild(confetti);
                    }

                    // Create the animation keyframes dynamically
                    const style = document.createElement('style');
                    style.innerHTML = `
                    @keyframes fall {
                        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
                        100% { transform: translateY(${window.innerHeight}px) rotate(360deg); opacity: 0; }
                    }
                `;
                    document.head.appendChild(style);
                }
                // Call once on page load
                createConfetti();
                // Optional: Call every few seconds for continuous effect
                // Optional: Create new confetti every few seconds for continuous effect
                // Uncomment the line below to enable
                setInterval(() => {
                    createConfetti();
                }, 5000);
            });
        </script>
    @endif
</div>

@extends('layouts.app')

@section('content')
    <div class="py-12 max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="mt-4 text-2xl font-bold text-gray-900">Thank You for Your Participation!</h2>
            <p class="mt-2 text-gray-600">Your responses to the {{ $response->survey->title }} have been recorded.</p>

            <div class="mt-6 p-4 bg-gray-50 rounded-md">
                <p class="text-sm text-gray-600 mb-1">Your completion code:</p>
                <div class="flex items-center justify-center space-x-2">
                    <span class="text-xl font-bold text-blue-700 tracking-wide">{{ $response->completion_code }}</span>
                    <button onclick="copyToClipboard('{{ $response->completion_code }}')" class="text-gray-500 hover:text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                    </button>
                </div>
                <p class="mt-4 text-sm text-gray-600">
                    Please save this code. You'll need it to claim any rewards if your response is selected as a winner.
                </p>
            </div>

            <div class="mt-8">
                <a href="{{ route('surveys.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Return to Surveys
                </a>
            </div>
        </div>
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
@endsection

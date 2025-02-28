<!-- resources/views/surveys/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-900">{{ $survey->title }}</h1>
                <p class="mt-2 text-gray-600">{{ $survey->description }}</p>
            </div>
            <div class="p-6">
                <p class="mb-6 text-gray-600">
                    This survey contains {{ $survey->questions->count() }} questions and will help shape the CPA profession.
                    Upon completion, you'll receive a unique code that will give you a chance to win a $100 Visa gift card.
                </p>

                <div class="flex justify-end">
                    <a href="{{ route('surveys.take', $survey) }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Start Survey
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

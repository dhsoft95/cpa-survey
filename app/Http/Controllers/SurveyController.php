<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('surveys.index');
    }

    public function show(Survey $survey): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('surveys.show', compact('survey'));
    }

    public function take(Survey $survey): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        // Check if the survey is active
        if (!$survey->isActive()) {
            return redirect()->route('surveys.index')
                ->with('error', 'This survey is not currently available.');
        }

        return view('surveys.take', compact('survey'));
    }
}

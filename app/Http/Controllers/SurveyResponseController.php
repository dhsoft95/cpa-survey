<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Models\Survey;
use App\Services\EIScoreCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SurveyResponseController extends Controller
{
    protected $eiScoreService;

    public function __construct(EIScoreCalculationService $eiScoreService)
    {
        $this->eiScoreService = $eiScoreService;
    }

    /**
     * Show the form to check results with completion code
     */
    public function checkForm()
    {
        return view('results.check-form');
    }

    /**
     * Verify completion code and redirect to results
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'completion_code' => 'required|string'
        ]);

        $response = SurveyResponse::where('completion_code', $request->completion_code)->first();

        if (!$response) {
            return back()->with('error', 'Invalid completion code. Please check and try again.');
        }

        // Check if this is an EI survey
        $isEISurvey = str_contains(strtolower($response->survey->title), 'emotional intelligence');

        if ($isEISurvey) {
            return redirect()->route('ei-results', ['completionCode' => $response->completion_code]);
        } else {
            // For regular surveys, just show a thank you page
            return view('results.thank-you', ['response' => $response]);
        }
    }

    /**
     * Download EI report PDF
     */
    public function downloadEIReport($code)
    {
        $response = SurveyResponse::where('completion_code', $code)
            ->with(['survey', 'answers.question.options'])
            ->firstOrFail();

        // Check if this is an EI survey
        $isEISurvey = str_contains(strtolower($response->survey->title), 'emotional intelligence');

        if (!$isEISurvey) {
            abort(404, 'This survey does not have an EI report available.');
        }

        $eiScores = $this->eiScoreService->calculateScores($response);
        $eiInterpretation = $this->eiScoreService->generateInterpretation($eiScores);

        // Create PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ei-report', [
            'response' => $response,
            'eiScores' => $eiScores,
            'eiInterpretation' => $eiInterpretation
        ]);

        return $pdf->download('emotional-intelligence-report.pdf');
    }
}

<?php
// routes/web.php
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('surveys.index');
});

// Public survey routes
Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
Route::get('/surveys/{survey}/take', [SurveyController::class, 'take'])->name('surveys.take');

// Survey results routes
Route::get('/results/check', [SurveyResponseController::class, 'checkForm'])->name('results.check');
Route::post('/results/verify', [SurveyResponseController::class, 'verifyCode'])->name('results.verify');

// EI Results - use the Livewire component with the correct syntax
Route::get('/ei-results/{completionCode}', function ($completionCode) {
    return view('ei-results', ['completionCode' => $completionCode]);
})->name('ei-results');

Route::get('/ei-report/{code}/download', [SurveyResponseController::class, 'downloadEIReport'])->name('ei-report.download');

// Admin routes (will be protected by middleware in a real app)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/responses/{response}/ei-report', [SurveyResponseController::class, 'downloadEIReport'])->name('ei-report.download');
});

Route::get('/surveys/{survey}', [App\Http\Controllers\SurveyController::class, 'show'])
    ->name('surveys.show');

// Survey analytics route
Route::get('/surveys/{survey}/analytics', [App\Http\Controllers\SurveyAnalyticsController::class, 'index'])
    ->name('surveys.analytics')
    ->middleware(['auth']);

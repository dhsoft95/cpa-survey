<?php

namespace App\Livewire;

use App\Models\Survey;
use Livewire\Component;

class SurveyList extends Component
{
    public function render()
    {
        $surveys = Survey::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->get();
        return view('livewire.survey-list', [
            'surveys' => $surveys,
        ]);
    }
}

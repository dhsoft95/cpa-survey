<?php

namespace App\Livewire;

use App\Models\SurveyResponse;
use Livewire\Component;
use Livewire\WithPagination;

class Winners extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        // Get the winners data
        $winners = SurveyResponse::query()
            ->with('survey') // Eager load the survey relationship
            ->where('is_winner', true)
            ->whereNotNull('completed_at')
            ->latest('updated_at')
            ->paginate(10); // Paginate with 10 winners per page

        // Return the view with the winners data
        return view('livewire.winners', [
            'winners' => $winners
        ]);
    }
}

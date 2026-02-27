<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Registrant;

class RegistrantFiltered extends Component
{
    use WithPagination;

    public $filter; // 'approved' or 'unapproved'
    public $title;
    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function mount($filter, $title)
    {
        $this->filter = $filter;
        $this->title = $title;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $registrants = Registrant::where('registration_status', $this->filter)
            ->when($this->search, fn($q) => $q->where('full_name', 'like', '%' . $this->search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.backend.registrant-filtered', ['registrants' => $registrants])
            ->layout('layouts.backend');
    }
}

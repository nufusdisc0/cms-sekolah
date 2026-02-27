<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Theme;

class ThemeManager extends Component
{
    use WithPagination;

    public $confirmingActivateId = null;
    protected $paginationTheme = 'bootstrap';

    public function activate($id)
    {
        Theme::query()->update(['theme_is_active' => 'false']);
        Theme::findOrFail($id)->update(['theme_is_active' => 'true']);
        $this->confirmingActivateId = null;
        session()->flash('message', 'Tema berhasil diaktifkan.');
    }

    public function confirmActivate($id)
    {
        $this->confirmingActivateId = $id;
    }
    public function cancelActivate()
    {
        $this->confirmingActivateId = null;
    }

    public function render()
    {
        $themes = Theme::paginate(10);
        return view('livewire.backend.theme-manager', ['themes' => $themes])
            ->layout('layouts.backend');
    }
}

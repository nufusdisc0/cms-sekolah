<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use App\Models\Setting;

class SettingsManager extends Component
{
    public $group;
    public $title;
    public $settings = [];

    public function mount($group, $title)
    {
        $this->group = $group;
        $this->title = $title;
        $this->loadSettings();
    }

    private function loadSettings()
    {
        $items = Setting::where('setting_group', $this->group)->get();
        $this->settings = [];
        foreach ($items as $item) {
            $this->settings[$item->setting_variable] = $item->setting_value ?? '';
        }
    }

    public function save()
    {
        foreach ($this->settings as $variable => $value) {
            Setting::where('setting_group', $this->group)
                ->where('setting_variable', $variable)
                ->update(['setting_value' => $value, 'updated_by' => auth()->id()]);
        }
        session()->flash('message', 'Pengaturan berhasil disimpan.');
    }

    public function render()
    {
        $settingRows = Setting::where('setting_group', $this->group)->get();
        return view('livewire.backend.settings-manager', ['settingRows' => $settingRows])
            ->layout('layouts.backend');
    }
}

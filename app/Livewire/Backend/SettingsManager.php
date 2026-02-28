<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;

class SettingsManager extends Component
{
    use WithFileUploads;

    public $group;
    public $title;
    public $settings = [];
    public $uploads = [];

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
        $this->uploads = [];
        foreach ($items as $item) {
            $this->settings[$item->setting_variable] = $item->setting_value ?? '';
        }
    }

    public function save()
    {
        foreach ($this->settings as $variable => $value) {
            $updateData = ['updated_by' => auth()->id()];

            // Check if there is an uploaded file for this setting
            if (isset($this->uploads[$variable]) && $this->uploads[$variable]) {
                $file = $this->uploads[$variable];
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/media_library/images', $filename);
                $updateData['setting_value'] = $filename;
                $this->settings[$variable] = $filename; // update Local state
            }
            else {
                $updateData['setting_value'] = $value;
            }

            Setting::where('setting_group', $this->group)
                ->where('setting_variable', $variable)
                ->update($updateData);
        }

        $this->uploads = []; // Reset uploads
        session()->flash('message', 'Pengaturan berhasil disimpan.');
    }

    public function render()
    {
        $settingRows = Setting::where('setting_group', $this->group)->get();
        return view('livewire.backend.settings-manager', ['settingRows' => $settingRows])
            ->layout('layouts.backend');
    }
}

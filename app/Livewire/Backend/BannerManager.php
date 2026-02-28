<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $banner_image;
    public $existing_banner_image;
    public $banner_title;
    public $banner_url;
    public $banner_position = 'dashboard_top'; // Default position
    public $banner_order = 0;
    public $status = 'Aktif';
    public $bannerId;
    public $isEditMode = false;

    protected $rules = [
        'banner_title' => 'nullable|string|max:255',
        'banner_url' => 'nullable|string|max:255',
        'banner_position' => 'required|string|max:50',
        'banner_order' => 'required|integer',
        'status' => 'required|in:Aktif,Nonaktif',
        'banner_image' => 'nullable|image|max:2048', // 2MB Max
    ];

    public function render()
    {
        $banners = Banner::orderBy('banner_order', 'asc')->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.backend.banner-manager', ['banners' => $banners])
            ->layout('layouts.backend');
    }

    public function resetInputFields()
    {
        $this->banner_title = '';
        $this->banner_url = '';
        $this->banner_position = 'dashboard_top';
        $this->banner_order = 0;
        $this->status = 'Aktif';
        $this->banner_image = null;
        $this->existing_banner_image = null;
        $this->bannerId = null;
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $bannerData = [
            'banner_title' => $this->banner_title,
            'banner_url' => $this->banner_url,
            'banner_position' => $this->banner_position,
            'banner_order' => $this->banner_order,
            'status' => $this->status,
        ];

        if ($this->banner_image) {
            $imageName = time() . '_' . $this->banner_image->getClientOriginalName();
            $this->banner_image->storeAs('public/banners', $imageName);
            $bannerData['banner_image'] = $imageName;
        }
        else {
            $bannerData['banner_image'] = ''; // Ensure not null if required
        }

        Banner::create($bannerData);

        session()->flash('message', 'Banner / Iklan berhasil ditambahkan.');
        $this->resetInputFields();
        $this->dispatch('close-modal');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        $this->bannerId = $id;
        $this->banner_title = $banner->banner_title;
        $this->banner_url = $banner->banner_url;
        $this->banner_position = $banner->banner_position;
        $this->banner_order = $banner->banner_order;
        $this->status = $banner->status;
        $this->existing_banner_image = $banner->banner_image;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate();

        $banner = Banner::find($this->bannerId);

        $bannerData = [
            'banner_title' => $this->banner_title,
            'banner_url' => $this->banner_url,
            'banner_position' => $this->banner_position,
            'banner_order' => $this->banner_order,
            'status' => $this->status,
        ];

        if ($this->banner_image) {
            // Delete old image if exists
            if ($banner->banner_image) {
                Storage::delete('public/banners/' . $banner->banner_image);
            }
            $imageName = time() . '_' . $this->banner_image->getClientOriginalName();
            $this->banner_image->storeAs('public/banners', $imageName);
            $bannerData['banner_image'] = $imageName;
        }

        $banner->update($bannerData);

        session()->flash('message', 'Banner / Iklan berhasil diperbarui.');
        $this->resetInputFields();
        $this->dispatch('close-modal');
    }

    public function delete($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->banner_image) {
            Storage::delete('public/banners/' . $banner->banner_image);
        }
        $banner->delete();
        session()->flash('message', 'Banner / Iklan berhasil dihapus.');
    }
}

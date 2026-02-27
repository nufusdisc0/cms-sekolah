<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Menu;

class MenuManager extends Component
{
    use WithPagination;

    public $menu_title = '';
    public $menu_url = '';
    public $menu_target = '_self';
    public $menu_type = 'links';
    public $editingId = null;
    public $showModal = false;
    public $confirmingDeleteId = null;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'menu_title' => 'required|string|max:255',
            'menu_url' => 'required|string|max:255',
            'menu_target' => 'required|in:_self,_blank',
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function edit($id)
    {
        $m = Menu::findOrFail($id);
        $this->editingId = $m->id;
        $this->menu_title = $m->menu_title;
        $this->menu_url = $m->menu_url;
        $this->menu_target = $m->menu_target ?? '_self';
        $this->menu_type = $m->menu_type ?? 'links';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'menu_title' => $this->menu_title,
            'menu_url' => $this->menu_url,
            'menu_target' => $this->menu_target,
            'menu_type' => $this->menu_type,
        ];
        if ($this->editingId) {
            Menu::findOrFail($this->editingId)->update(array_merge($data, ['updated_by' => auth()->id()]));
            session()->flash('message', 'Menu berhasil diperbarui.');
        }
        else {
            Menu::create(array_merge($data, ['created_by' => auth()->id()]));
            session()->flash('message', 'Menu berhasil ditambahkan.');
        }
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }
    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }
    public function delete($id)
    {
        Menu::findOrFail($id)->delete();
        $this->confirmingDeleteId = null;
        session()->flash('message', 'Menu berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->menu_title = '';
        $this->menu_url = '';
        $this->menu_target = '_self';
        $this->menu_type = 'links';
    }

    public function render()
    {
        $menus = Menu::where('is_deleted', 'false')
            ->orderBy('menu_order')
            ->paginate(20);

        return view('livewire.backend.menu-manager', ['menus' => $menus])
            ->layout('layouts.backend');
    }
}

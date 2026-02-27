<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Option;

class OptionManager extends Component
{
    use WithPagination;

    public $optionGroup;
    public $title;
    public $fieldLabel;

    // Form
    public $option_name = '';
    public $editingId = null;
    public $showModal = false;
    public $search = '';
    public $confirmingDeleteId = null;

    protected $paginationTheme = 'bootstrap';

    public function mount($group, $title, $fieldLabel = null)
    {
        $this->optionGroup = $group;
        $this->title = $title;
        $this->fieldLabel = $fieldLabel ?? $title;
    }

    protected function rules()
    {
        return ['option_name' => 'required|string|max:255'];
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
        $item = Option::findOrFail($id);
        $this->editingId = $item->id;
        $this->option_name = $item->option_name;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'option_group' => $this->optionGroup,
            'option_name' => $this->option_name,
        ];

        if ($this->editingId) {
            Option::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Data berhasil diperbarui.');
        }
        else {
            $data['created_by'] = auth()->id();
            Option::create($data);
            session()->flash('message', 'Data berhasil ditambahkan.');
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
        Option::findOrFail($id)->delete();
        $this->confirmingDeleteId = null;
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->option_name = '';
    }

    public function render()
    {
        $items = Option::where('option_group', $this->optionGroup)
            ->where('is_deleted', 'false')
            ->when($this->search, fn($q) => $q->where('option_name', 'like', '%' . $this->search . '%'))
            ->orderBy('option_name')
            ->paginate(10);

        return view('livewire.backend.option-manager', ['items' => $items])
            ->layout('layouts.backend');
    }
}

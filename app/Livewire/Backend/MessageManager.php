<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Comment;

class MessageManager extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingId = null;
    public $comment_subject = '';
    public $comment_reply = '';
    public $confirmingDeleteId = null;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'comment_subject' => 'required|string|max:255',
            'comment_reply' => 'required|string',
        ];
    }

    public function view($id)
    {
        $msg = Comment::findOrFail($id);
        $this->editingId = $msg->id;
        $this->comment_subject = $msg->comment_subject ?? '';
        $this->comment_reply = $msg->comment_reply ?? '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function reply()
    {
        $this->validate();
        $msg = Comment::findOrFail($this->editingId);
        $msg->update([
            'comment_subject' => $this->comment_subject,
            'comment_reply' => $this->comment_reply,
            'comment_status' => 'approved',
            'updated_by' => auth()->id(),
        ]);
        session()->flash('message', 'Balasan berhasil dikirim.');
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
        Comment::findOrFail($id)->delete();
        $this->confirmingDeleteId = null;
        session()->flash('message', 'Pesan berhasil dihapus.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $messages = Comment::where('comment_type', 'message')
            ->when($this->search, fn($q) => $q->where('comment_author', 'like', '%' . $this->search . '%')
        ->orWhere('comment_content', 'like', '%' . $this->search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.backend.message-manager', ['messages' => $messages])
            ->layout('layouts.backend');
    }
}

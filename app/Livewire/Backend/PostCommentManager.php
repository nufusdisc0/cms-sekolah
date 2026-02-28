<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Comment;

class PostCommentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $editingId = null;
    public $showModal = false;
    public $comment_content = '';
    public $comment_status = 'unapproved';
    public $confirmingDeleteId = null;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'comment_content' => 'required|string',
            'comment_status' => 'required|in:approved,unapproved,spam',
        ];
    }

    public function edit($id)
    {
        $c = Comment::findOrFail($id);
        $this->editingId = $c->id;
        $this->comment_content = $c->comment_content ?? '';
        $this->comment_reply = $c->comment_reply ?? '';
        $this->comment_status = $c->comment_status ?? 'unapproved';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();
        Comment::findOrFail($this->editingId)->update([
            'comment_content' => $this->comment_content,
            'comment_reply' => $this->comment_reply,
            'comment_status' => $this->comment_status,
            'updated_by' => auth()->id(),
        ]);
        session()->flash('message', 'Komentar berhasil diperbarui.');
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
        session()->flash('message', 'Komentar berhasil dihapus.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $comments = Comment::where('comment_type', 'post')
            ->when($this->search, fn($q) => $q->where('comment_content', 'like', '%' . $this->search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.backend.post-comment-manager', ['comments' => $comments])
            ->layout('layouts.backend');
    }
}

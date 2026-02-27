<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;

class PostManager extends Component
{
    use WithPagination, WithFileUploads;

    // Form fields
    public $post_title = '';
    public $post_content = '';
    public $post_categories = '';
    public $post_status = 'publish';
    public $post_visibility = 'public';
    public $post_comment_status = 'open';
    public $post_tags = '';
    public $post_image;

    // Component state
    public $editingPostId = null;
    public $showModal = false;
    public $search = '';
    public $confirmingDeleteId = null;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_categories' => 'nullable|string',
            'post_status' => 'required|in:publish,draft',
            'post_visibility' => 'required|in:public,private',
            'post_comment_status' => 'required|in:open,close',
            'post_tags' => 'nullable|string',
            'post_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
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
        $post = Post::findOrFail($id);
        $this->editingPostId = $post->id;
        $this->post_title = $post->post_title;
        $this->post_content = $post->post_content;
        $this->post_categories = $post->post_categories ?? '';
        $this->post_status = $post->post_status ?? 'publish';
        $this->post_visibility = $post->post_visibility ?? 'public';
        $this->post_comment_status = $post->post_comment_status ?? 'open';
        $this->post_tags = $post->post_tags ?? '';
        $this->post_image = null;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'post_title' => $this->post_title,
            'post_content' => $this->post_content,
            'post_categories' => $this->post_categories ?: null,
            'post_type' => 'post',
            'post_status' => $this->post_status,
            'post_visibility' => $this->post_visibility,
            'post_comment_status' => $this->post_comment_status,
            'post_slug' => Str::slug($this->post_title),
            'post_tags' => $this->post_tags ?: null,
        ];

        if ($this->post_image) {
            $data['post_image'] = $this->post_image->store('posts', 'public');
        }

        if ($this->editingPostId) {
            $post = Post::findOrFail($this->editingPostId);
            $post->update($data);
            session()->flash('message', 'Tulisan berhasil diperbarui.');
        }
        else {
            $data['post_author'] = auth()->id();
            Post::create($data);
            session()->flash('message', 'Tulisan berhasil ditambahkan.');
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
        Post::findOrFail($id)->delete();
        $this->confirmingDeleteId = null;
        session()->flash('message', 'Tulisan berhasil dihapus.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->editingPostId = null;
        $this->post_title = '';
        $this->post_content = '';
        $this->post_categories = '';
        $this->post_status = 'publish';
        $this->post_visibility = 'public';
        $this->post_comment_status = 'open';
        $this->post_tags = '';
        $this->post_image = null;
    }

    public function render()
    {
        $posts = Post::where('post_type', 'post')
            ->when($this->search, function ($query) {
            $query->where('post_title', 'like', '%' . $this->search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::where('category_type', 'post')->get();

        return view('livewire.backend.post-manager', compact('posts', 'categories'))
            ->layout('layouts.backend');
    }
}

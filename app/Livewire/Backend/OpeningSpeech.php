<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use App\Models\Post;

class OpeningSpeech extends Component
{
    public $post_content = '';

    public function mount()
    {
        $post = Post::where('post_type', 'opening_speech')->first();
        $this->post_content = $post->post_content ?? '';
    }

    protected function rules()
    {
        return ['post_content' => 'required|string'];
    }

    public function save()
    {
        $this->validate();
        Post::updateOrCreate(
        ['post_type' => 'opening_speech'],
        ['post_content' => $this->post_content, 'post_title' => 'Sambutan Kepala Sekolah', 'post_author' => auth()->id()]
        );
        session()->flash('message', 'Sambutan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.backend.opening-speech')
            ->layout('layouts.backend');
    }
}

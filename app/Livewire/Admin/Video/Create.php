<?php

namespace App\Livewire\Admin\Video;

use App\Models\Video;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\WireUiActions;

class Create extends ModalComponent
{
    use WireUiActions;

    #[Validate('required|min:3')]
    public $title = '';

    #[Validate('required|min:3')]
    public $description = '';

    #[Validate('required|min:3')]
    public $url = '';

    public function store(): void
    {

        $embedUrl = $this->convertToEmbedUrl($this->url);

        Video::create([

            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'embedurl' => $embedUrl,
        ]);

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Video Created Successfully',
            'description' => 'You have added a new video',
        ]);

        $this->reset();

        $this->dispatch('refreshVideos');

        $this->closeModal();
    }

    private function convertToEmbedUrl($url)
    {
        // Check if the URL is a shortened youtu.be link
        if (preg_match('/youtu\.be\/([^\?]+)(.*)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . $matches[2];
        }

        // Check if the URL is a full youtube.com/watch?v= link
        if (preg_match('/youtube\.com\/watch\?v=([^\&]+)(.*)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . $matches[2];
        }

        // Return original URL if it doesn't match known patterns (fallback)
        return $url;
    }


    public function render()
    {
        return view('livewire.admin.video.create');
    }
}

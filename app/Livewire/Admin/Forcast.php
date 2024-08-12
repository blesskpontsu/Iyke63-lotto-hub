<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use App\Models\Forcast as ModelsForcast;
use Intervention\Image\Facades\Image;
use WireUi\Traits\WireUiActions;

class Forcast extends Component
{
    use WithFileUploads, WireUiActions;

    #[Validate('required|min:3')]
    public string $company = '';
    #[Validate('required|min:3')]
    public string $game = '';
    #[Validate('required|min:3')]
    public string $draw_time = '';
    #[Validate('required|min:3')]
    public $image;


    public function store(): void
    {
        $forcast = new ModelsForcast([
            'company' => $this->company,
            'game' => $this->game,
            'game_time' => $this->draw_time
        ]);

        if ($this->image) {
            $imageName = $this->image->getClientOriginalName();
            $newImageName = Str::slug(pathinfo($imageName, PATHINFO_FILENAME));
            $imageExtension = time() . '.' . $newImageName . '.' . $this->image->getClientOriginalExtension();
            $location = 'public/forcast/' . $imageExtension;
            $image = Image::make($this->image);
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $imageStream = $image->stream();
            Storage::disk('local')->put($location, $imageStream->__toString());
        }
        $forcast->image = $imageExtension;
        $forcast->save();

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Forcast Created Successfully',
            'description' => 'You have created new forcast',
        ]);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.forcast');
    }
}

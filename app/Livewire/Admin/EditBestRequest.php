<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\RequestBet;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\Validate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class EditBestRequest extends Component
{
    use WireUiActions, WithFileUploads;

    public $request;
    public $image;
    public $disabled = true;

    public function mount(RequestBet $request)
    {
        $this->request = $request;
        $this->image = $request->image; // Initialize status with current value
        $this->updatedImage();
    }

    public function updatedImage()
    {
        // Validate or set disabled state based on image availability
        $this->disabled = empty($this->image);
    }

    public function update()
    {
        $this->validate([
            'image' => 'required|image|max:2048',  // Ensure it's an image and has size limit
        ]);

        if ($this->image) {
            $originalExtension = $this->image->getClientOriginalExtension();
            $finalExtension = $originalExtension === 'jpeg' ? 'jpg' : $originalExtension;
            $imageName = str_replace(' ', '_', pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME));
            $imageFileName = time() . '_' . $imageName . '.' . $finalExtension;
            $location = 'public/bet-requests/' . $imageFileName;
            $image = Image::make($this->image);
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $imageStream = $image->stream();
            Storage::disk('local')->put($location, $imageStream->__toString());

            $this->request->update([
                'status' => 'staked',
                'image' => $imageFileName
            ]);
        }

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Bet Request Updated Successfully',
            'description' => 'You have updated the bet request',
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('refreshBets');
        $this->dispatch('betUpdated');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.edit-best-request');
    }
}

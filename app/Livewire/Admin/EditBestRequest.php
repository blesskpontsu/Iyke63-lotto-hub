<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\RequestBet;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class EditBestRequest extends Component
{
    use WireUiActions, WithFileUploads;

    public $request;
    public $image;

    public function mount(RequestBet $request)
    {
        $this->request = $request;
        $this->image = $request->image; // Initialize status with current value
    }

    public function update()
    {
        if ($this->image) {
            $imageName = $this->image->getClientOriginalName();
            $newImageName = str_replace(' ', '_', pathinfo($imageName, PATHINFO_FILENAME));
            $imageExtension = time() . '.' . $newImageName . '.' . $this->image->getClientOriginalExtension();
            $location = 'public/bet-requests/' . $imageExtension;
            $image = Image::make($this->image);
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $imageStream = $image->stream();
            Storage::disk('local')->put($location, $imageStream->__toString());

            $this->request->update([
                'status' => 'staked',
                'image' => $imageExtension
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

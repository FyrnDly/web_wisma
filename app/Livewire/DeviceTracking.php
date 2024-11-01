<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class DeviceTracking extends Component
{
    public $room_id;
    public $device_positions = [];
    public $plot_url;

    public function mount($roomId)
    {
        $this->room_id = $roomId;
        $this->updateData();
    }

    public function updateData()
    {
        $api = env("FLASK_API", "http://localhost:5000");
        $response = Http::get($api."api/positions", [
            'room_id' => $this->room_id,
        ]);

        $data = $response->json();
        $this->device_positions = $data['device_positions'];
        $this->plot_url = $data['plot_url'];
    }

    public function render()
    {
        return view('livewire.device-tracking');
    }
}

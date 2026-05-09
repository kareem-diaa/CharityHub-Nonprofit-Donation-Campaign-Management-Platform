<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class CampaignProgressBar extends Component
{
    public Campaign $campaign;

    public function render()
    {
        // Re-fetch from the database so the progress bar updates if someone else donates
        $this->campaign->refresh();
        
        return view('livewire.campaign-progress-bar');
    }
}

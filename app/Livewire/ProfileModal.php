<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class ProfileModal extends Component
{
    public $user;
    public $userId;

    // Listen for the event to load a user profile
    protected $listeners = ['loadProfile' => 'loadUser'];

    /**
     * Load the user for the modal
     */
    public function loadUser($userId)
    {
        $this->userId = $userId;
        $this->user = User::with(['interests', 'photos'])->find($userId);
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.profile-modal');
    }
}

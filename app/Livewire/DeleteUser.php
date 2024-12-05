<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class DeleteUser extends Component
{
    public $userId;

    public function mount($userId)
    {
        $this->userId = $userId;
    }

    public function deleteUser()
    {


        $user = User::findOrFail($this->userId);
        $status = $user->delete();

        if($status){
            $this->dispatch('alertDispatched', ['message' => 'Usuario eliminado correctamente', 'class' => 'toast-success']);
            $this->dispatch('userDeleted');
        }
        else{
            $this->dispatch('alertDispatched', ['message' => 'Error al eliminar el usuario', 'class' => 'toast-danger']);
        }
    }

    public function render()
    {
        return view('livewire.delete-user');
    }
}

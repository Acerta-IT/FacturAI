<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Mail\UserResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class UsersList extends Component
{
    public $term;
    public $department;
    public $role;

    public $successMessage;

//    public $loadingUserId = null;
//
//    public $isSendingEmail = false;

    protected $listeners = ['filter' => 'search', 'userDeleted' => 'render'];


    public function mount()
    {
        $this->isSendingEmail = false;
    }

    public function search($term, $department, $role)
    {
        $this->term = $term;
        $this->department = $department;
        $this->role = $role;
    }

    public function resetPassword(User $user)
    {
        $this->loadingUserId = $user->id; // Establecer el ID del usuario en proceso
        try {
            $this->isSendingEmail = true;
            $token = Password::broker()->createToken($user);
            $resetUrl = url("/reset-password/$token?email={$user->email}");

            Mail::to($user->email)->send(new UserResetPassword($resetUrl));

            $this->dispatch('alertDispatched', [
                'message' => 'Correo enviado correctamente.',
                'class' => 'toast-success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('alertDispatched', [
                'message' => 'Error al enviar el correo.',
                'class' => 'toast-danger',
            ]);
        }

        $this->loadingUserId = null; // Restablecer después de completar la acción
        $this->isSendingEmail = false;
    }


    public function render()
    {
        $users = User::when($this->term, function ($query) {
            $query->where('name', 'LIKE', "%" . $this->term . "%")
                ->orWhere('surname', 'LIKE', "%" . $this->term . "%")
                ->orWhere('email', 'LIKE', "%" . $this->term . "%");
        })
            ->when($this->department, function ($query) {
                // Verificar si se ha seleccionado un departamento
                if ($this->department !== 'Seleccionar') {
                    $query->where('department', $this->department);
                }
            })
            ->when($this->role, function ($query) {
                // Verificar si se ha seleccionado un permiso
                if ($this->role !== 'Seleccionar') {
                    $query->where('role', $this->role);
                }
            })
            ->orderBy('name')
            ->get();

        return view('livewire.users-list', ['users' => $users]);
    }
}

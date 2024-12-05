<?php

namespace App\Livewire;

use App\Mail\UserResetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ResetPassword extends Component
{
    public $userId;

    public $loadingUserId = null;

    public $isSendingEmail = false;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->isSendingEmail = false;
    }

    public function resetPassword()
    {
        $user = User::findOrFail($this->userId);
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
        return view('livewire.reset-password');
    }
}

<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordLink extends Mailable
{
    use Queueable, SerializesModels;

    public string $link;
    public User $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $user = User::whereEmail($email)->first();

        if ($user) {
            $this->link = Password::createToken($user);
            $this->user = $user;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reset-password');
    }
}

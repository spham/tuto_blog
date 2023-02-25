<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AuthorLoginForm extends Component
{
    public $email, $password;

    public function LoginHandler()
    {
        $this->validate(
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:5'
            ],
            [
                'email.required' => 'Enter your email address',
                'email.email' => 'Invalid email address',
                'eamil.exists' => 'Thie email is not registered',
                'password.required' => 'Enter is required',
            ]
        );
        $creds = array('email' => $this->email, 'password' => $this->password);
        if (Auth::guard('web')->attempt($creds)) {
            $checkUser = User::where('email', $this->email)->first();
            if ($checkUser->blocked == 1) {
                Auth::guard('web')->logout();
                return redirect()->route('author.login')->with('fail', 'your account is blocked');
            } else {
                return redirect()->route('author.home');
            }
        } else {
            session()->flash('fail', 'Incorrect email or adresse');
        }
    }
    public function render()
    {
        return view('livewire.author-login-form');
    }
}

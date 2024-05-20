<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private $adminToken = 'admin5';
    private $teacherToken = 'nda5';
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'token' => ['required', 'string', 'max:255'],
        ]);


        if ($request->token == $this->adminToken) {
            $roleId = 1;
        } elseif ($request->token == $this->teacherToken) {
            $roleId = 2;
        } else {
            throw ValidationException::withMessages([
                'token' => ['Invalid registration token.'],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($user->isAdmin()) {
            return redirect()->intended(route('dashboard'));
        } else {
            // $user->isTeacher();
            return redirect()->intended(route('teacher'));
        }
        // return redirect(route('dashboard', absolute: false));
    }
}

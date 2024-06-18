<?php

namespace App\Http\Controllers\Auth;

use App\Enums\PrefixEnums;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'prefixname' => ['required', Rule::in(PrefixEnums::values())],
            'firstname'  => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname'   => 'required|string|max:255',
            'username'   => 'required|string|max:255',
            'email'      => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'prefixname'  => $request->get('prefixname'),
            'firstname'  => $request->get('firstname'),
            'lastname'   => $request->get('lastname'),
            'middlename' => $request->get('middlename'),
            'username'   => $request->get('username'),
            'email'      => $request->get('email'),
            'password'   => Hash::make($request->get('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'prefixes' => PrefixEnums::values(),
        ]);
    }
}

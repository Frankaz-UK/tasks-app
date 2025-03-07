<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisterStoreRequest $request): RedirectResponse
    {
        $user = User::create([
            'forename' => $request->input('forename'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'position' => $request->input('position'),
            'gender' => $request->input('gender'),
            'telephone' => $request->input('telephone'),
            'password' => Hash::make($request->input('password')),
        ]);

        $user->assignRole(['user']);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

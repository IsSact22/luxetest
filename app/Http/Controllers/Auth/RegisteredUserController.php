<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // // Asignar rol por defecto al usuario
            // $user->assignRole('cam');

            event(new Registered($user));

            Auth::login($user);
            
            \DB::commit();

            return redirect()->intended(route('dashboard'))->with('success', '¡Registro exitoso! Bienvenido.');
        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Error durante el registro: ' . $e->getMessage());
            
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Error al registrar el usuario. Por favor, intenta nuevamente.');
        }
    }
}

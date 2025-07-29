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
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Para depurar

class RegisteredUserController extends Controller
{
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
            'tipo_cliente' => ['required', 'string', 'in:B2C,B2B'],
            'razon_social' => ['nullable', 'string', 'max:255', 'required_if:tipo_cliente,B2B'],
            'rut_empresa' => ['nullable', 'string', 'max:20', 'required_if:tipo_cliente,B2B'],
        ]);

    // Construimos el cuerpo de la petición para nuestra API
        $apiData = [
            'nombre' => $request->name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password), // IMPORTANTE: Hasheamos la contraseña
            'tipo_cliente' => $request->tipo_cliente,
            'razon_social' => $request->razon_social,
            'rut_empresa' => $request->rut_empresa,
        ];
    
    // Asumimos que tienes el endpoint de clientes en tu config/ords.php
        $apiUrl = config('ords.clientes_create_endpoint');
    
        Log::info('Intentando registrar nuevo cliente en API: ' . $apiUrl, $apiData);

    // ¡AQUÍ IRÁ LA LLAMADA A LA API! Por ahora lo dejamos comentado
    // $response = Http::post($apiUrl, $apiData);
    //
    // if (!$response->successful()) {
    //     Log::error('Fallo al crear cliente en API', ['response' => $response->body()]);
    //     return back()->withErrors('No se pudo completar el registro en este momento.');
    // }

    // Creamos el usuario LOCAL en Laravel para que el login funcione
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);
    }
}

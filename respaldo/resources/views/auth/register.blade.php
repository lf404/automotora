<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <!-- Tipo de Cliente -->
        <div class="mt-4">
            <x-input-label for="tipo_cliente" :value="__('Tipo de Cliente')" />
            <select id="tipo_cliente" name="tipo_cliente" class="block mt-1 w-full" onchange="toggleB2BFields()">
                <option value="B2C" selected>Persona (B2C)</option>
                <option value="B2B">Empresa (B2B)</option>
            </select>
        </div>

        <!-- Campos B2B (ocultos por defecto) -->
        <div id="b2b_fields" style="display: none;">
            <div class="mt-4">
            <x-input-label for="razon_social" :value="__('Razón Social')" />
            <x-text-input id="razon_social" class="block mt-1 w-full" type="text" name="razon_social" :value="old('razon_social')" />
        </div>

        <div class="mt-4">
            <x-input-label for="rut_empresa" :value="__('RUT de la Empresa')" />
            <x-text-input id="rut_empresa" class="block mt-1 w-full" type="text" name="rut_empresa" :value="old('rut_empresa')" />
            </div>
        </div>

        <script>
            function toggleB2BFields() {
            var tipoCliente = document.getElementById('tipo_cliente').value;
            var b2bFields = document.getElementById('b2b_fields');
            if (tipoCliente === 'B2B') {
                b2bFields.style.display = 'block';
            } else {
                b2bFields.style.display = 'none';
            }
        }
        </script>

        <!-- Justo aquí empieza el div con el botón de Registrar -->
        <div class="flex items-center justify-end mt-4">
        <!-- ... -->
     </form>
</x-guest-layout>

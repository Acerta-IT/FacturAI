<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')"/>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                          autofocus autocomplete="name"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-input-label for="surname" :value="__('Surname')"/>
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')"
                          required autofocus autocomplete="surname"/>
            <x-input-error :messages="$errors->get('surname')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-input-label for="department" :value="__('Department')"/>
            <select id="department" name="department"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required>
                <option value="" disabled selected>{{ __('Seleccionar departamento') }}</option>
                @foreach (\App\Enums\Departments::cases() as $department)
                    <option value="{{ $department->value }}"
                        {{ old('department') == $department->value ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('department')" class="mt-2"/>
        </div>

        <div class="mt-4">
            <x-input-label for="rol" :value="__('Rol')"/>
            <select name="rol" id="rol"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required>
                <option value="" disabled selected>{{ __('Seleccionar permisos') }}</option>
                @foreach (\App\Enums\Role::cases() as $role)
                    <option value="{{ $role->value }}"
                        {{ old('rol') == $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('rol')" class="mt-2"/>
        </div>


        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                          required autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')"/>

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                          autocomplete="new-password"/>

            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                          name="password_confirmation" required autocomplete="new-password"/>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<x-app-layout>
    <x-slot name="header">
        Crear usuario
    </x-slot>
    <div class="flex justify-center mt-32 gap">
        <form method="POST" action="{{ route('user.create') }}" class="w-1/2 ">
            @csrf

            <!-- Name -->
            <div class="flex gap-8 mb-4">
                <div class="w-1/2">
                    <x-input-label for="name" :value="__('Nombre')"/>
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                                  autocomplete="name"/>
                    <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                </div>

                <div class="w-1/2">
                    <x-input-label for="surname" :value="__('Apellidos')"/>
                    <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname"
                                  :value="old('surname')" autocomplete="surname"/>
                    <x-input-error :messages="$errors->get('surname')" class="mt-2"/>
                </div>
            </div>

            <div class="flex gap-8 mb-4">
                <div class="mt-4 w-1/2">
                    <x-input-label for="department" :value="__('Departamento')"/>
                    <select id="department" name="department"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="" disabled selected>{{ __('Seleccionar departamento') }}</option>
                        @foreach (\App\Enums\Departments::cases() as $department)
                            <option value="{{ $department->value }}"
                                {{ old('department') == $department->value ? 'selected' : '' }}>
                                {{ $department->label() }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('department')" class="mt-2"/>
                </div>

                <div class="mt-4 w-1/2 mb-4">
                    <x-input-label for="role" :value="__('Perfil')"/>
                    <select name="role" id="role"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="" disabled selected>{{ __('Seleccionar permisos') }}</option>
                        @foreach (\App\Enums\Role::cases() as $role)
                            <option value="{{ $role->value }}"
                                {{ old('role') == $role->value ? 'selected' : '' }}>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2"/>
                </div>
            </div>


            <!-- Email Address -->
            <div class="mt-4  w-1/2 mb-4 pr-4">
                <x-input-label for="email" :value="__('Email')"/>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                              autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <div class="flex gap-8 mb-2"><!-- Password -->
                <div class="mt-4 w-1/2">
                    <x-input-label for="password" :value="__('Contraseña')"/>

                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                  autocomplete="new-password"/>

                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                </div>

                <!-- Confirm Password -->
                <div class="mt-4 w-1/2">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')"/>

                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                  name="password_confirmation" autocomplete="new-password"/>

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                </div>

            </div>
            <p class="text-sm text-neutral2 mt-0 pt-0">Si la contraseña se deja vacía, se enviará un correo de
                restablecimiento.</p>

            <div class="flex items-center mt-6">
                <x-primary-button class=" w-46">
                    {{ __('Crear usuario') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>

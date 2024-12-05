<div>
    <form wire:submit.prevent="readFormInput" class="flex">
        <div class="flex w-full justify-between gap-8">
            <div class="">
                <label class="" for="term">Término:</label>

                <input id="term" type="text" placeholder="Nombre, apellido o email"
                       class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                       wire:model="term"/>
            </div>
            <div class="">
                <label class="" for="term">Área:</label>
                <select class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        wire:model="department">
                    <option>Seleccionar</option>

                    @foreach ($userDepartments as $department)
                        <option value="{{ $department->value }}">
                            {{ \App\Enums\Departments::tryFrom($department->value)?->label() ?? 'Unknown' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="">
                <label class="" for="term">Perfil:</label>
                <select class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        wire:model="rol">
                    <option>Seleccionar</option>

                    @foreach ($userRol as $role)
                        <option value="{{ $role->value }}">
                            {{ \App\Enums\Role::tryFrom($role->value)?->label() ?? 'Unknown' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="">
                <x-primary-button>
                    {{ __('Buscar') }}
                </x-primary-button>
            </div>
        </div>
    </form>

</div>

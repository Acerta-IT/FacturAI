<div>
    <form wire:submit.prevent="execute">
        @csrf

        <div class="mb-8">
            {{-- <x-file-input id="directory_path"
                wire:model="directory_path"
                name="directory_path[]"
                label="Ruta del directorio"
                description="En esta ruta deben estar las facturas y el AnexoII"
            /> --}}
            <livewire:file-input />
            @error('filePaths')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

        </div>

        <div class="mb-4">
            <x-input-label for="clientName" :value="__('Nombre del cliente')" />
            <x-text-input id="clientName"
                class="block mt-1 w-full"
                type="text"
                wire:model="clientName"
                name="clientName"
                :value="$clientName" />
            @error('clientName')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-8">
            <div wire:loading wire:target="execute" class="mt-1 mr-2 flex flex-row items-center space-x-2">
                <x-spinner />
                <span class="flex-shrink-0 text-sm text-gray-600 w-10">
                    Ejecutando programa...
                </span>
            </div>

            @if($fileToDownload)
                <a
                    href="{{ asset('downloads/Registros-Primarios.xlsx') }}"
                    download="Registros-Primarios.xlsx"
                >
                    <x-primary-button type="button" class="mr-2">
                        Download
                    </x-primary-button>
                </a>
            @endif

            <x-primary-button wire:model="buttonDisabled" :disabled="$buttonDisabled">
                Ejecutar programa
            </x-primary-button>
        </div>


    </form>


</div>

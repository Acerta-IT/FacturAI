<div>
    <form wire:submit.prevent="execute">
        @csrf

        <div class="mb-8">
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
            @if($isProcessing)
                <div class="mt-1 mr-2 flex flex-row items-center space-x-2">
                    <x-spinner />
                    <span class="flex-shrink-0 text-sm text-gray-600">
                        Procesando archivos...
                    </span>
                </div>
            @endif

            @if($fileToDownload)
                <a href="{{ asset($fileToDownload) }}" download="{{$clientName . '_Registros-Primarios.xlsx'}}">
                    <x-primary-button type="button" class="mr-2">
                        Descargar resultado
                    </x-primary-button>
                </a>
            @endif

            <x-primary-button wire:model="buttonDisabled" :disabled="$buttonDisabled || $isProcessing">
                Ejecutar programa
            </x-primary-button>
        </div>

    </form>

    @if($isProcessing)
        <div wire:poll="checkFileStatus">
            <!-- This div will poll the server to check if the file is ready -->
        </div>
    @endif
</div>

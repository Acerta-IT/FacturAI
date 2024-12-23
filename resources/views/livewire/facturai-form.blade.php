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

        <div class="mb-4">
            <x-input-label for="projectId" :value="__('ID del proyecto')" />
            <x-text-input id="projectId"
                class="block mt-1 w-full"
                type="text"
                wire:model="projectId"
                name="projectId"
                :value="$projectId" />
            @error('projectId')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-16">
            <x-primary-button wire:model="buttonDisabled" :disabled="$buttonDisabled">
                Ejecutar programa
            </x-primary-button>
        </div>

    </form>
</div>

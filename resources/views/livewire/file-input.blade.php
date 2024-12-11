<div class="">
    <div class="">
        <x-input-label for="files" :value="__('Seleccionar archivos')" class="mb-2"/>
        <input
            type="file"
            name="files[]"
            wire:model="files"
            id="files"
            webkitdirectory
            multiple
            x-on:change="$wire.dispatch('uploadStarted')"
        />
        <p class="text-sm text-gray-500 mt-2">{{ __('En esta carpeta deben estar las facturas y el AnexoII') }}</p>
    </div>

    <div wire:loading wire:target="files" class="mt-1 flex flex-row items-center space-x-2">
        <x-spinner />
        <span class="flex-shrink-0 text-sm text-gray-600 w-10">
            Subiendo archivos...
        </span>
    </div>
</div>

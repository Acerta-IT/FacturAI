{{--
    Component: nav-dropdown
    Description: Creates a collapsible dropdown menu item with a title, icon, and nested links.

    Props:
    - `dropDownId` (integer): Unique identifier for the dropdown, used to manage its toggle state. Default: `0`.
    - `icon` (string): The name of the Material Symbols icon to display next to the dropdown title. Default: `''`.
    - `title` (string): The text displayed as the title of the dropdown. Default: `''`.

    Behavior:
    - The dropdown toggles its visibility when clicked, showing or hiding its content.
    - Uses Alpine.js for state management (`toggle()` and `isActive()` functions must be defined in the parent scope).

    Usage Example:
    <x-nav-dropdown dropDownId=0 icon="settings" title="Gestión de usuarios">
        <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')" level="2">
            <x-slot name="icon">group</x-slot>
            Usuarios
        </x-nav-link>
    </x-nav-dropdown>
--}}


@props(['dropDownId' => 0, 'icon' => '', 'title' => ''])


<div class="px-4 pb-2">
    <!-- Encabezado colapsable -->
    <div @click="toggle({{$dropDownId}})"
         class="flex justify-between items-center cursor-pointer text-neutral2 px-4 py-2 rounded-md hover:text-neutral4">
                    <span class="flex gap-2 items-center">
                        <span class="material-symbols-outlined">{{ $icon }}</span>
                        {{ $title }}
                    </span>
        <span class="material-symbols-outlined"
              x-text="isActive(0) ? 'expand_more' : 'chevron_right'"></span>
    </div>


    <div x-show="isActive({{$dropDownId}})" x-transition:enter="transition-all linear duration-75"
         x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen"
         x-transition:leave="transition-all linear duration-75"
         x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0"
         class="overflow-hidden">
        {{ $slot }}
    </div>
</div>

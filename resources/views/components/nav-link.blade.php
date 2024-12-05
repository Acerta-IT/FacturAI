{{--
    Component: nav-link
    Description: Generates a styled navigation link with support for active states, icons, and nesting levels.

    Props:
    - `active` (boolean): Indicates if the link is active. Adds specific styles when true. Default: `false`.
    - `level` (integer): Determines the nesting level of the link.
        - `1`: Top level option (`px-4`).
        - `2`: Inside a dropdown (`px-6`).
      Default: `1`.
    - `icon` (string): The name of the Material Symbols icon to display alongside the link. Default: `''`.

    Usage Example:
    <x-nav-link :active="request()->routeIs('dashboard')" level="2" icon="settings">
        Dashboard
    </x-nav-link>
--}}

@props(['active', 'level' => 1, 'icon' => ''])

@php
    $paddingClass = $level == 1 ? ' px-4' : ' px-6';

    $classes =
        $active ?? false
            ? 'text-neutral4 py-2 rounded-md drop-shadow-md w-full bg-white flex gap-2 items-center border-l-6 border-brand'
            : 'text-neutral2 py-2 rounded-md  flex gap-2 items-center  border-l-6 border-secondary hover:border-white hover:bg-white hover:border-neutral1 hover:text-neutral hover:drop-shadow-md';

    $classes .= $paddingClass;
@endphp

@if($level === 1)
    <div class="px-2 pb-2">
        @endif
        <div class="p-1">
            <a {{ $attributes->merge(['class' => $classes]) }}>
        <span class="material-symbols-outlined text-xl">
            {{ $icon ?? '' }}
        </span>
                {{ $slot }}
            </a>
        </div>
        @if($level === 1)
    </div>
@endif

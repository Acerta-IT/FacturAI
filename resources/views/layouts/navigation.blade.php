<aside x-data="{
    activeIndexes: [],
    init() {
        // Get saved state from localStorage
        const savedIndexes = JSON.parse(localStorage.getItem('activeIndexes'));
        this.activeIndexes = Array.isArray(savedIndexes) ? savedIndexes : [];
    },
    toggle(index) {
        // If index is active, toggle it; otherwise, add it
        if (this.activeIndexes.includes(index)) {
            this.activeIndexes = this.activeIndexes.filter(i => i !== index);
        } else {
            this.activeIndexes.push(index);
        }

        // Save state to localStorage
        localStorage.setItem('activeIndexes', JSON.stringify(this.activeIndexes));
    },
    isActive(index) {
        // Verify if index is in active array
        return this.activeIndexes.includes(index);
    }
}"
       class="top-0 left-0 z-40 w-80 h-dvh transition-transform -translate-x-full sm:translate-x-0 bg-secondary flex flex-col justify-between text-neutral2 shadow-md">

    <!-- Top content -->
    <div>
        <div class="flex justify-center mt-8">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-20 fill-current text-gray-800 justify-center"/>
            </a>
        </div>

        <hr class="mx-4 border-neutral1 mt-8">
        <div class="flex gap-4 ml-4 my-4 items-center">
            <div>
                <img src="{{ asset('user placeholder.png') }}" alt="Logo"
                     class="w-12 h-12 rounded-full border-2 border-neutral1">
            </div>
            <div>
                <p><span class="text-neutral">{{ auth()->user()->name . ' ' . auth()->user()->surname }}</span></p>
                <p><span class="text-neutral2 text-sm">{{ auth()->user()->email }}</span></p>
            </div>
        </div>
        <hr class="mx-4 border-neutral1 mb-10">

        <!-- Contenedor con scroll solo para los x-nav-link -->
        <div class="flex flex-col overflow-y-auto max-h-[60vh] select-none">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-slot name="icon">dashboard</x-slot>
                Dashboard
            </x-nav-link>

            <!-- Dropdown 1 -->
            @if(auth()->user()->role_enum === \App\enums\Role::Admin)
                <x-nav-dropdown dropDownId=0 icon="settings" title="Gestón de usuarios">
                    <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')" level="2">
                        <x-slot name="icon">group</x-slot>
                        Usuarios
                    </x-nav-link>
                </x-nav-dropdown>
            @endif

        </div>

    </div>

    <!-- Sección inferior (logout) -->
    <div class="mb-5 flex flex-col mt-10 gap-4">
        <form method="POST" action="{{ route('logout') }}" class="flex items-center px-4 py-3 w-full text-left gap-2">
            @csrf

            <button type="submit" class="flex hover:text-neutral4">
                <span class="material-symbols-outlined">
                    logout
                </span>
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</aside>

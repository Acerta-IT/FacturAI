<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="">
                Trabajos completados
            </div>

            @if(auth()->user()->role === \App\enums\Role::Admin->value)
                <button
                    data-modal-target="clean-modal"
                    data-modal-toggle="clean-modal"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined">delete_forever</span>
                    Borrar todo
                </button>

                <!-- Delete Modal -->
                <div id="clean-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <div class="relative bg-white rounded-lg shadow">
                            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="clean-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                            </button>
                            <div class="p-4 md:p-8 text-center">
                                <svg class="mx-auto mb-8 text-neutral4 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                <h3 class="mb-8 text-lg font-normal text-neutral4">¿Seguro que quieres eliminar todos los proyectos y sus archivos?</h3>
                                <form action="{{ route('completed-jobs.clean') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                                        Eliminar
                                    </button>
                                </form>
                                <button data-modal-hide="clean-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="mx-10 2xl:mx-20">
        <livewire:completed-jobs-list />
    </div>
</x-app-layout>

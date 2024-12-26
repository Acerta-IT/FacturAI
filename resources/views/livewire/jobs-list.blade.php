<div>
    <div class="flex pb-2 border-b border-gray-300">
        <div class="w-1/5 text-center">
            <p>ID Proyecto</p>
        </div>
        <div class="w-1/5 text-center">
            <p>Cliente</p>
        </div>
        <div class="w-1/5 text-center">
            <p>Creado</p>
        </div>
        <div class="w-1/5 text-center">
            <p>En ejecución</p>
        </div>
        <div class="w-1/5 text-center">
            <p>Acciones</p>
        </div>
    </div>

    @forelse ($pendingJobs as $job)
        <div class="flex items-center py-2 border-b border-gray-200">
            <div class="w-1/5 text-center">
                <p>{{ $job->projectId }}</p>
            </div>
            <div class="w-1/5 text-center">
                <p>{{ $job->clientName }}</p>
            </div>
            <div class="w-1/5 text-center">
                <p>{{ date('d-m-Y H:i', strtotime($job->created_at)) }}</p>
            </div>
            <div class="w-1/5 text-center">
                <p>{{ $job->processing ? 'Sí' : 'No' }}</p>
            </div>
            <div class="w-1/5 text-center">
                @if(!$job->processing)
                <button type="button"
                    wire:click="setJobIdToDelete({{ $job->id }})"
                    class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center mt-4">
            <p class="text-gray-400">No hay trabajos pendientes</p>
        </div>
    @endforelse

    <!-- Delete Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto" style="display: {{ $showDeleteModal ? 'flex' : 'none' }}">
        <div class="fixed inset-0 bg-gray-500 opacity-75" wire:click="closeModal"></div>

        <div class="relative p-4 w-full max-w-md max-h-full z-10 m-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" wire:click="closeModal"
                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-neutral4 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-neutral4 dark:text-neutral4">Seguro que quieres eliminar este trabajo?</h3>
                    <button type="button"
                        wire:click="deleteJob"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                        Eliminar
                    </button>
                    <button wire:click="closeModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($pendingJobs->isNotEmpty())
        <div class="mt-4">
            {{ $pendingJobs->links() }}
        </div>
    @endif
</div>

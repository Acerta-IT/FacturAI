<div>
    <div class="flex pb-2 border-b border-gray-300">
        <div class="w-1/5 text-center">
            <p>ID</p>
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
            <p>Intentos</p>
        </div>
    </div>

    @forelse ($pendingJobs as $job)
        <div class="flex items-center py-2 border-b border-gray-200">
            <div class="w-1/5 text-center">
                <p>{{ $job->id }}</p>
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
                <p>{{ $job->attempts }}</p>
            </div>
        </div>
    @empty
        <div class="text-center mt-4">
            <p class="text-gray-400">No hay trabajos pendientes</p>
        </div>
    @endforelse

    @if($pendingJobs->isNotEmpty())
        <div class="mt-4">
            {{ $pendingJobs->links() }}
        </div>
    @endif
</div>

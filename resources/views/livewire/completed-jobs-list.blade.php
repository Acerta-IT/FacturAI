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
            <p>Duración</p>
        </div>
        <div class="w-1/5 text-center">
            <p>Acciones</p>
        </div>
    </div>

    @forelse ($completedJobs as $job)
        <div class="flex items-center py-2 border-b border-gray-200">
            <div class="w-1/5 text-center">
                <p>{{ $job->project_id }}</p>
            </div>
            <div class="w-1/5 text-center">
                <p>{{ $job->client_name }}</p>
            </div>
            <div class="w-1/5 text-center">
                <p>{{ date('d-m-Y H:i', strtotime($job->created_at)) }}</p>
            </div>
            <div class="w-1/5 text-center">
                <p>{{ \Carbon\Carbon::parse($job->reserved_at)->diffForHumans($job->completed_at, true) }}</p>
            </div>
            <div class="w-1/5 text-center">
                @if($job->downloadUrl)
                    <div class="flex justify-center">
                        <a href="{{ asset($job->downloadUrl) }}" class="inline-flex items-center" download>
                            <x-icon-button icon="download"/>
                        </a>
                    </div>
                @else
                    <span class="text-gray-400">Archivo no disponible</span>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center mt-4">
            <p class="text-gray-400">No hay trabajos completados</p>
        </div>
    @endforelse

    @if($completedJobs->isNotEmpty())
        <div class="mt-4">
            {{ $completedJobs->links() }}
        </div>
    @endif
</div>

<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Recent Projects -->
        @foreach ($recentProjects as $project)
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-2">{{ $project->name }}</h3>
                <p class="text-gray-600 mb-4">{{ $project->description }}</p>
                <div class="text-sm text-gray-500">
                    <p>Status: <span class="font-semibold">{{ ucfirst($project->status) }}</span></p>
                    <p>Priority: <span class="font-semibold">{{ ucfirst($project->priority) }}</span></p>
                    <p>Start Date: <span class="font-semibold">{{ $project->start_date }}</span></p>
                    <p>End Date: <span class="font-semibold">{{ $project->end_date }}</span></p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="col-span-2 bg-white rounded-lg shadow-lg p-4">
    <h3 class="text-xl font-bold mb-4">Task Details</h3>
    <div class="space-y-4">
        <p class="text-lg"><strong>Name:</strong> {{ $selectedTask->name }}</p>
        <p><strong>Description:</strong> {{ $selectedTask->description }}</p>
        <p><strong>Due Date:</strong> {{ $selectedTask->due_date }}</p>
        <p><strong>Priority:</strong> {{ ucfirst($selectedTask->priority) }}</p>
        <p><strong>Assigned To:</strong> {{ $selectedTask->assigned_to ? $selectedTask->assignedTo->name : 'Unassigned' }}</p>
        <p><strong>Created By:</strong> {{ $selectedTask->creator->name ?? '' }}</p>
        <p><strong>Completed:</strong> {{ $selectedTask->is_completed ? 'Yes' : 'No' }}</p>
    </div>
    <h3 class="text-xl font-bold mt-4 mb-4">Comments</h3>
    <ul class="divide-y divide-gray-200 mb-4">
        @foreach($comments as $comment)
            <li class="py-2 flex justify-between items-center">
                <div>
                    <p>{{ $comment->comment }}</p>
                    <p class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }} by {{ $comment->user->name }}</p>
                </div>
            </li>
        @endforeach
    </ul>
    <form wire:submit.prevent="addComment" class="mb-4">
        <textarea wire:model.debounce.500ms="newComment" placeholder="Add a comment" class="p-2 border rounded w-full @error('newComment') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
        @error('newComment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        <button type="submit" class="p-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 mt-2">
            Add Comment
        </button>
    </form>
</div>

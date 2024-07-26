<div class="container mx-auto p-4">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks for Project: ') . $project->name }}
        </h2>
    </x-slot>

    <div>
        @if (session()->has('message'))
            <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-4" role="alert">
                <div class="flex">
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-4" role="alert">
                <div class="flex">
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center mb-4 mx-5">
            <button wire:click="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Task
            </button>

            <div class="flex space-x-4">
                <input type="text" wire:model.debounce.300ms="search" placeholder="Search..." class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <select wire:model="filterPriority" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <button wire:click="applyFilters" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </div>
        </div>

        @if($isOpen)
            @include('livewire.task.form-task')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="col-span-1 overflow-x-auto bg-gray-50 rounded-lg shadow-lg p-4 ">
                <h3 class="text-xl font-bold mb-2">Tasks</h3>
                <ul class="space-y-2">
                    @foreach($tasks as $task)
                        <li class="bg-white p-2 rounded-lg shadow-md border border-gray-200 hover:bg-gray-100 cursor-pointer" wire:click="selectTask({{ $task->id }})">
                            <div class="flex justify-between items-center">
                                <span class="text-blue-500 hover:underline">{{ $task->name }}</span>
                                <span class="text-sm {{ $task->is_completed ? 'text-green-500' : 'text-yellow-500' }}">
                                    {{ $task->is_completed ? 'Completed' : 'Pending' }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <button wire:click="editTask({{ $task->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Edit</button>
                                <button wire:click="deleteTask({{ $task->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Delete</button>
                                <button wire:click="toggleComplete({{ $task->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                    {{ $task->is_completed ? 'Mark as Pending' : 'Mark as Completed' }}
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if($selectedTask)
                @livewire('comments.task-comments', ['taskId' => $selectedTask->id])
            @endif
        </div>

        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    </div>
</div>

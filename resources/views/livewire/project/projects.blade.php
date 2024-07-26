<!-- Update your existing project view -->
<div class="container mx-auto p-4">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div>
        @if (session()->has('message'))
            <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-4"
                 role="alert">
                <div class="flex">
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center mb-4 mx-5">
            <button wire:click="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Project
            </button>

            <div class="flex space-x-4">
                <input type="text" wire:model.debounce.300ms="search" placeholder="Search..."
                       class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <select wire:model="filterStatus"
                        class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
                <select wire:model="filterPriority"
                        class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <button wire:click="applyFilters"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </div>
        </div>

        @if($isOpen)
            @include('livewire.project.form-project')
        @endif

        @if($isInviteOpen)
            @include('livewire.project.invite-user')
        @endif

        @if($isShowUsersOpen)
            @include('livewire.project.show-users')
        @endif

        <div class="overflow-x-auto">
            <table class="table-auto w-full bg-gray-50 rounded-lg shadow-lg">
                <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-center">Name</th>
                    <th class="px-4 py-2 text-center">Description</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Start Date</th>
                    <th class="px-4 py-2 text-center">End Date</th>
                    <th class="px-4 py-2 text-center">Priority</th>
                    <th class="px-4 py-2 text-center">Task Count</th>
                    <th class="px-4 py-2 text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    <tr class="bg-white border-b hover:bg-gray-100">
                        <td class="border px-4 py-2 text-center">{{ $project->name }}</td>
                        <td class="border px-4 py-2 text-center">{{ $project->description }}</td>
                        <td class="border px-4 py-2 text-center">{{ ucfirst($project->status) }}</td>
                        <td class="border px-4 py-2 text-center">{{ $project->start_date }}</td>
                        <td class="border px-4 py-2 text-center">{{ $project->end_date }}</td>
                        <td class="border px-4 py-2 text-center">{{ ucfirst($project->priority) }}</td>
                        <td class="border px-4 py-2 text-center">{{ ucfirst($project->tasks->count()) }}</td>
                        <td class="border px-4 py-2 text-center">
                            <div class="inline-flex space-x-2">
                                <button wire:click="edit({{ $project->id }})"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Edit
                                </button>
                                <button wire:click="delete({{ $project->id }})"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Delete
                                </button>
                                <button wire:click="viewTasks({{ $project->id }})"
                                        class="bg-amber-500 hover:bg-amber-700 text-white font-bold py-1 px-2 rounded">View Tasks
                                </button>
                                <button wire:click="openInviteModal({{ $project->id }})"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Invite
                                </button>
                                <button wire:click="showInvitedUsers({{ $project->id }})"
                                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded">Show Invited Users
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>

</div>

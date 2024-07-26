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
                    <th class="px-4 py-2 text-center">Created By</th>
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
                        <td class="border px-4 py-2 text-center">{{ ucfirst($project->user->name) }}</td>

                        <td class="border px-4 py-2 text-center">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <div>
                                    <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                        Actions
                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                        <a href="#" wire:click="edit({{ $project->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Edit</a>
                                        <a href="#" wire:click="delete({{ $project->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Delete</a>
                                        <a href="#" wire:click="viewTasks({{ $project->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">View Tasks</a>
                                        <a href="#" wire:click="openInviteModal({{ $project->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Invite</a>
                                        <a href="#" wire:click="showInvitedUsers({{ $project->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Show Invited Users</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

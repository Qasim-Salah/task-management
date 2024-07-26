<?php

namespace App\Livewire\Projects;

use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Projects extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $status = 'pending';
    public $start_date;
    public $end_date;
    public $priority = 'medium';
    public $projectId;
    public $isEdit = false;
    public $isOpen = false;
    public $isInviteOpen = false;
    public $isShowUsersOpen = false;
    public $search = '';
    public $filterStatus = '';
    public $filterPriority = '';
    public $inviteUserId;
    public $users;
    public $invitedUsers = [];

    protected $rules = [
        'name' => 'required|string|max:255|unique:projects,name',
        'description' => 'nullable|string|max:1000',
        'status' => 'required|in:pending,in_progress,completed',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'priority' => 'required|in:low,medium,high',
    ];

    public function mount()
    {
        $authUserId = Auth::id();

        $this->users = User::where('id', '<>', $authUserId)->get();
    }

    public function render()
    {
        $authUserId = Auth::id();

        $query = Project::where(function ($query) use ($authUserId) {
            $query->where('user_id', $authUserId)
                ->orWhereHas('members', function ($query) use ($authUserId) {
                    $query->where('user_id', $authUserId);
                });
        })->with('tasks');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterPriority) {
            $query->where('priority', $this->filterPriority);
        }

        $projects = $query->latest()->paginate(10);

        return view('livewire.project.projects', [
            'projects' => $projects,
        ])->layout('layouts.app');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetFields()
    {
        $this->reset(['name', 'description', 'status', 'start_date', 'end_date', 'priority', 'projectId', 'isEdit', 'inviteUserId']);
    }

    public function create()
    {
        $this->resetFields();
        $this->isOpen = true;
    }
    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function store()
    {
        $this->validate();

        Project::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'priority' => $this->priority,
        ]);

        session()->flash('message', 'Project created successfully.');

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $this->fill($project->only('name', 'description', 'status', 'start_date', 'end_date', 'priority'));
        $this->projectId = $project->id;
        $this->isEdit = true;
        $this->isOpen = true;
    }

    public function update()
    {
        $this->rules['name'] = 'required|string|max:255|unique:projects,name,' . $this->projectId;

        $this->validate();

        $project = Project::findOrFail($this->projectId);
        $project->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'priority' => $this->priority,
        ]);

        session()->flash('message', 'Project updated successfully.');

        $this->closeModal();
        $this->resetFields();
    }

    public function delete($id)
    {
        Project::findOrFail($id)->delete();

        session()->flash('message', 'Project deleted successfully.');
    }

    public function viewTasks($projectId)
    {
        return redirect()->route('tasks', ['projectId' => $projectId]);
    }

    public function applyFilters()
    {
        // This method will be triggered when the "Search" button is clicked.
        // It will simply rerender the component with the updated search and filter values.
    }

    public function openInviteModal($projectId)
    {
        $this->projectId = $projectId;
        $this->isInviteOpen = true;
    }

    public function closeInviteModal()
    {
        $this->isInviteOpen = false;
    }

    public function inviteUser()
    {
        $this->validate(['inviteUserId' => 'required|exists:users,id']);

        $user = User::findOrFail($this->inviteUserId);

        $project = Project::findOrFail($this->projectId);

        $project->members()->attach($user->id);

        session()->flash('message', 'User invited successfully.');

        $this->closeInviteModal();
        $this->reset(['inviteUserId']);
    }

    public function showInvitedUsers($projectId)
    {
        $this->projectId = $projectId;
        $this->invitedUsers = Project::findOrFail($projectId)->members()->get();
        $this->isShowUsersOpen = true;
    }

    public function closeShowUsersModal()
    {
        $this->isShowUsersOpen = false;
    }
}

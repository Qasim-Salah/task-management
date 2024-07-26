<?php

namespace App\Livewire\Tasks;

use App\Models\Project\Project;
use App\Models\Task\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Tasks extends Component
{
    use WithPagination;

    public $projectId;
    public $name;
    public $description;
    public $due_date;
    public $priority = 'medium';
    public $is_completed = false;
    public $taskId;
    public $isEdit = false;
    public $isOpen = false;
    public $search = '';
    public $filterPriority = '';
    public $assigned_to;
    public $users;
    public $selectedTask;
    public $comments;
    public $newComment;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'nullable|date',
        'priority' => 'required|in:low,medium,high',
        'assigned_to' => 'nullable|exists:users,id',
        'newComment' => 'nullable|string|max:1000',
    ];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->users = User::all();
    }

    public function render()
    {
        $query = Task::where('project_id', $this->projectId)->with(['project', 'assignedTo', 'creator', 'comments']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterPriority) {
            $query->where('priority', $this->filterPriority);
        }

        $tasks = $query->latest()->paginate(10);

        return view('livewire.task.tasks', [
            'tasks' => $tasks,
            'project' => Project::find($this->projectId),
        ])->layout('layouts.app');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetFields()
    {
        $this->reset(['name', 'description', 'due_date', 'priority', 'is_completed', 'taskId', 'isEdit', 'assigned_to', 'selectedTask', 'comments', 'newComment']);
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

    public function saveTask()
    {
        $this->validate();

        Task::create([
            'project_id' => $this->projectId,
            'name' => $this->name,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'is_completed' => $this->is_completed,
            'assigned_to' => $this->assigned_to,
            'user_id' => Auth::id(),
        ]);

        session()->flash('message', 'Task created successfully.');

        $this->closeModal();
        $this->resetFields();
    }

    public function editTask($id)
    {
        $task = Task::findOrFail($id);
        $this->fill($task->only('name', 'description', 'due_date', 'priority', 'is_completed', 'assigned_to'));
        $this->taskId = $task->id;
        $this->isEdit = true;
        $this->isOpen = true;
    }

    public function updateTask()
    {
        $this->validate();

        $task = Task::findOrFail($this->taskId);
        $task->update([
            'name' => $this->name,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'is_completed' => $this->is_completed,
            'assigned_to' => $this->assigned_to,
        ]);

        session()->flash('message', 'Task updated successfully.');

        $this->closeModal();
        $this->resetFields();
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            session()->flash('message', 'Task deleted successfully.');
        } else {
            session()->flash('error', 'Task not found.');
        }

        $this->resetFields();
    }
    public function toggleComplete($id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);
    }

    public function selectTask($taskId)
    {
        $this->selectedTask = Task::with(['comments.user', 'assignedTo', 'creator'])->findOrFail($taskId);
        $this->comments = $this->selectedTask->comments;
    }

    public function applyFilters()
    {
        // This method will be triggered when the "Search" button is clicked.
        // It will simply rerender the component with the updated search and filter values.
    }
}


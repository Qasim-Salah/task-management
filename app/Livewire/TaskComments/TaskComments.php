<?php

namespace App\Livewire\TaskComments;


use App\Models\Task\Task;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TaskComments extends Component
{
    public $taskId;
    public $selectedTask;
    public $comments;
    public $newComment;

    protected $rules = [
        'newComment' => 'required|string|max:1000',
    ];

    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $this->selectedTask = Task::with(['comments.user', 'assignedTo', 'creator'])->findOrFail($taskId);
        $this->comments = $this->selectedTask->comments;
    }

    public function render()
    {
        return view('livewire.comments.task-comments');
    }

    public function addComment()
    {
        $this->validate();

        $comment = $this->selectedTask->comments()->create([
            'task_id' => $this->selectedTask->id,
            'user_id' => Auth::id(),
            'comment' => $this->newComment,
        ]);

        $this->comments->prepend($comment);
        $this->newComment = '';
    }
}


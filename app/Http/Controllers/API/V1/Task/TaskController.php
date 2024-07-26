<?php

namespace App\Http\Controllers\API\V1\Task;

use App\Constants\ErrorCodes;
use App\Http\Controllers\API\V1\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\Task\TaskShowResource;
use App\Models\Project\Project;
use App\Models\Task\CommentTask as CommentTaskModel;
use App\Models\Task\Task;
use App\Models\Task\Task as TaskModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class TaskController extends Controller
{
    private $task;

    private $commentTask;

    public function __construct(TaskModel $task, CommentTaskModel $commentTask)
    {
        $this->task = $task;
        $this->commentTask = $commentTask;
    }

    public function index($projectId)
    {
        $tasks = $this->task->where('project_id', $projectId)->latest()->get();

        $tasksResource = TaskResource::collection($tasks);

        return ResponseBuilder::success(['tasks' => $tasksResource]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',

        ],
            [
                'name.required' => 'The name field is required.',
                'description.string' => 'The description must be a string.',
                'description.max' => 'The description may not be greater than 1000 characters.',
                'due_date.date' => 'The start date is not a valid date.',
                'priority.required' => 'The priority field is required.',
                'priority.in' => 'The selected priority is invalid.',
                'assigned_to' => 'Assigned To'
            ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
        }

        $requests = $validator->validated();
        $requests['user_id'] = Auth::user()->id;

        $this->task->create($requests);

        return ResponseBuilder::success(null, 'Data has been saved successfully', 200);
    }

    public function show($id, $projectId)
    {
        $tasksResource = $this->task->with(['creator', 'assignedTo', 'project', 'comments'])->where('project_id', $projectId)->findOrFail($id);

        return ResponseBuilder::success(['patient' => new TaskShowResource($tasksResource)]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
        ],
            [
                'name.required' => 'The name field is required.',
                'description.string' => 'The description must be a string.',
                'description.max' => 'The description may not be greater than 1000 characters.',
                'due_date.date' => 'The start date is not a valid date.',
                'priority.required' => 'The priority field is required.',
                'priority.in' => 'The selected priority is invalid.',
                'assigned_to' => 'Assigned To'
            ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
        }

        $task = $this->task->findOrFail($id);

        $requests = $validator->validated();

        $task->update($requests);

        return ResponseBuilder::success(null, 'Data has been Updated successfully', 200);
    }

    public function destroy($id, $projectId)
    {
        $task = $this->task->where('project_id', $projectId)->findOrFail($id);

        if ($task) {
            $task->delete();
            return ResponseBuilder::success(null, 'Data has been Deleted successfully', 200);
        }

        return ResponseBuilder::error('Data has been Deleted successfully', 200);
    }


    public function storeComment(Request $request, $taskId)
    {

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:255',
        ],
            [
                'comment.required' => 'The name field is required.',
            ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
        }

        $task = $this->task->findorFail($taskId);

        $requests = $validator->validated();
        $requests['user_id'] = Auth::user()->id;
        $requests['task_id'] = $task->id;

        $this->commentTask->create($requests);

        return ResponseBuilder::success(null, 'Data has been saved successfully', 200);

    }
}

<?php

namespace App\Http\Controllers\API\V1\Project;

use App\Constants\ErrorCodes;
use App\Http\Controllers\API\V1\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Project\ProjectShowResource;
use App\Models\Project\Project as ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProjectController extends Controller
{
    private $project;

    public function __construct(ProjectModel $project)
    {
        $this->project = $project;
    }

    public function index()
    {
        $projects = $this->project->forUser()->latest()->get();

        $projectsResource = ProjectResource::collection($projects);

        return ResponseBuilder::success(['projects' => $projectsResource]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,in_progress,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high',
        ],
            [
                'name.required' => 'The name field is required.',
                'name.unique' => 'The project name has already been taken.',
                'description.string' => 'The description must be a string.',
                'description.max' => 'The description may not be greater than 1000 characters.',
                'status.required' => 'The status field is required.',
                'status.in' => 'The selected status is invalid.',
                'start_date.date' => 'The start date is not a valid date.',
                'end_date.date' => 'The end date is not a valid date.',
                'end_date.after_or_equal' => 'The end date must be a date after or equal to the start date.',
                'priority.required' => 'The priority field is required.',
                'priority.in' => 'The selected priority is invalid.',
            ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
        }

        $requests = $validator->validated();
        $requests['user_id'] = Auth::user()->id;

        $this->project->create($requests);

        return ResponseBuilder::success(null, 'Data has been saved successfully', 200);
    }

    public function show($id)
    {
        $projectsResource = $this->project->with(['user', 'tasks', 'members'])->findOrFail($id);

        return ResponseBuilder::success(['patient' => new ProjectShowResource($projectsResource)]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,in_progress,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high',
        ],
            [
                'name.required' => 'The name field is required.',
                'name.unique' => 'The project name has already been taken.',
                'description.string' => 'The description must be a string.',
                'description.max' => 'The description may not be greater than 1000 characters.',
                'status.required' => 'The status field is required.',
                'status.in' => 'The selected status is invalid.',
                'start_date.date' => 'The start date is not a valid date.',
                'end_date.date' => 'The end date is not a valid date.',
                'end_date.after_or_equal' => 'The end date must be a date after or equal to the start date.',
                'priority.required' => 'The priority field is required.',
                'priority.in' => 'The selected priority is invalid.',
            ]);
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
        }

        $project = $this->project->findOrFail($id);

        $requests = $validator->validated();

        $project->update($requests);

        return ResponseBuilder::success(null, 'Data has been Updated successfully', 200);
    }

    public function destroy($id)
    {
        $project = $this->project->findOrFail($id);

        if ($project) {
            $project->delete();
            return ResponseBuilder::success(null, 'Data has been Deleted successfully', 200);
        }

        return ResponseBuilder::error('Data has been Deleted successfully', 200);
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project\Project as ProjectModel;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{

    public function __invoke()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return ResponseBuilder::error('Unauthorized', 401);
        }

        // Get the latest 10 projects for the authenticated user
        $projects = ProjectModel::forUser()->latest()->take(10)->get();

        $projectsResource = ProjectResource::collection($projects);

        return ResponseBuilder::success(['projects' => $projectsResource]);

    }
}

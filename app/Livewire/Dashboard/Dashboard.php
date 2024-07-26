<?php

namespace App\Livewire\Dashboard;

use App\Models\Project\Project as ProjectModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $recentUsers;
    public $recentProjects;
    public $projectData;


    public function mount()
    {
        $this->recentProjects = ProjectModel::forUser()->latest()->take(10)->get();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
}

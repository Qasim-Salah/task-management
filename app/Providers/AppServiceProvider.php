<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Livewire::component('project.projects', \App\Livewire\Projects\Projects::class);
        Livewire::component('task.tasks', \App\Livewire\Tasks\Tasks::class);
        Livewire::component('dashboard.dashboard', \App\Livewire\Dashboard\Dashboard::class);
        Livewire::component('comments.task-comments', \App\Livewire\TaskComments\TaskComments::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

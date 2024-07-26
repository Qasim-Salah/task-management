<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'project_name' => $this->project->name,
            'title' => $this->name,
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'assigned_to' => $this->assignedTo->name,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'created_by' => $this->creator->name,
            'created_at' => $this->created_at,
        ];
    }
}

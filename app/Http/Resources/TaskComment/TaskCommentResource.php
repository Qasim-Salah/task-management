<?php

namespace App\Http\Resources\TaskComment;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskCommentResource extends JsonResource
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
            'task' => $this->task->name,
            'created_by' => $this->user->name,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'school_id' => $this->school_id,
            'user_id' => $this->user_id,
            'department_id' => $this->department_id,
            'start' => $this->start,
//            'end' => $this->end,
            'school' => $this->schools->name,
            'department' => $this->department->name,
            'user' => $this->user->name,
//            'visitor' => $this->visitor,
        ];
    }
}

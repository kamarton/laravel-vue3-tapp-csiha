<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TaskResource extends JsonResource
{
    private bool $withShortDescription = false;

    public function withShortDescription(): self
    {
        $this->withShortDescription = true;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'completed_at' => $this->completed_at,
            'spent_time' => $this->spent_time,
            'estimated_time' => $this->estimated_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if (null !== $this->description && $this->withShortDescription) {
            $array['description'] = Str::limit($this->description, 64, '…');;
        } else {
            $array['description'] = $this->description;
        }

        if (null !== $this->assignedUser) {
            $array['assigned_user'] = (new UserResource($this->assignedUser))->toArray($request);
        }
        return $array;
    }
}

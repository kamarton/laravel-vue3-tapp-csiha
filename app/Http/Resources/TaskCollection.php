<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $this->collection->transform(function (TaskResource $model) {
            return $model->withShortDescription();
        });

        return parent::toArray($request);
    }

}

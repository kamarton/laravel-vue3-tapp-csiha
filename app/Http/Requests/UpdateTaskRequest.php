<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends StoreTaskRequest
{
    public function rules(): array
    {
        $rules = array_merge(parent::rules(), [
            'completed_at' => ['nullable', 'date'],
        ]);

        $rules['name'] = $this->getNameRule(false);
        return $rules;
    }
}

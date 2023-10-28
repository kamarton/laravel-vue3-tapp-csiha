<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => $this->getNameRule(),
            'description' => ['nullable', 'string', 'max:4096'],
            'spent_time_add' => ['nullable', 'integer', 'min:0'],
            'estimated_time' => ['nullable', 'integer', 'min:0'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    protected function getNameRule(bool $required = true): array
    {
        $rule = ['string', 'max:255'];
        if ($required) {
            array_unshift($rule, 'required');
        }
        return $rule;
    }
}

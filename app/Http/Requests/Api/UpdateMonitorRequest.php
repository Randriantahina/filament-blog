<?php

namespace App\Http\Requests\Api;

use App\Enums\MonitorType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMonitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(array_column(MonitorType::cases(), 'value'))],
            'url' => ['sometimes', 'nullable', 'url'],
            'check_interval_minutes' => ['sometimes', 'integer', 'min:1'],
            'port' => ['sometimes', 'nullable', 'integer'],
            'keyword' => ['sometimes', 'nullable', 'string'],
        ];
    }
}

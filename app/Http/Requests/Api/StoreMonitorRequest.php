<?php

namespace App\Http\Requests\Api;

use App\Enums\MonitorType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMonitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_column(MonitorType::cases(), 'value'))],
            'url' => ['required_if:type,http', 'nullable', 'url'],
            'check_interval_minutes' => ['sometimes', 'integer', 'min:1'],
            'port' => ['required_if:type,port', 'nullable', 'integer'],
            'keyword' => ['required_if:type,keyword', 'nullable', 'string'],
        ];
    }
}

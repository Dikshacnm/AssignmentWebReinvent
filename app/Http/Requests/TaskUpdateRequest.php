<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskUpdateRequest extends FormRequest 
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
     public function rules()
    {

        return [
            'task_id'=> 'required|integer|exists:tasks,id',
        ];
    }

    public function messages():array
    {
        return[
                'task_id.required' => 'The task id is required.',
                'task_id.exists' => 'Invalid task Id',
                'task_id.integer' => 'Task id should be an integer',
        ];

    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(
            sendErrorResponse('Validation failed', $errors ,422)
        );
    }
}

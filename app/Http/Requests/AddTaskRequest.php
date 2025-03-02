<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddTaskRequest extends FormRequest
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
            'task_detail'=> 'required|string|unique:tasks|max:200',
            'task_created_by'=> 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'task_date_time'=> 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

    public function messages():array
    {
        return[
                'task_detail.required' => 'The task field is required.',
                'task_detail.unique' => 'Another task with the same name has already been added.',
                'task_detail.string' => 'The task detail field should be a valid text.',
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

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|unique:users|max:255',
            'last_name' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|max:255',
            'phone' => 'required|unique:users|max:255',
            'password' => 'required|unique:users|max:255',
        ];

    
    }

    
    public function failedValidation(ValidationValidator $validator)

    {

        throw new HttpResponseException(response(['data'=>$validator->errors(),'msg'=>false] ));

    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactUsRequest extends FormRequest
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
     * @return array
     */

    public function rules()
    {
        if ($this->getMethod() == 'POST') {
            $rules = [
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'description' => 'required',
            ];
        } else {
            $rules = [
                'reply' => 'required',
                'status' => 'required',
            ];
        }
        return $rules;
    }

    /**
     * Get the validation messages of rules that applied to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email field requires an email',
            'subject.required' => 'Subject field is required',
            'description.required' => 'Description is required',
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */

    public function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => $validator->errors()->all(),
                'result' => false
            ], 422));
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}

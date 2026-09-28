<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CareerRequest extends FormRequest
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
        return [
            'designation'  => ['required', 'max:255'],
            'company'      => ['required', 'max:255'],
            'start'        => ['required', 'numeric'],
            'end'          => ['numeric', 'nullable'],
        ];
    }

    /**
     * Get the validation messages of rules that applied to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'designation.required'   => translate('Designation field is required'),
            'designation.max'        => translate('Designation field Max 255 characters'),
            'company.required'       => translate('Company field is required'),
            'company.max'            => translate('Company fieldPartner Max 100 characters'),
            'start.required'         => translate('Career Start field is required'),
            'start.numeric'          => translate('Career Start field must be numeric'),
            'end.numeric'            => translate('Career end field must be numeric')
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

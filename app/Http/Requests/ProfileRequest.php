<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'first_name'    => [ 'required','max:255'],
            'last_name'     => [ 'required','max:255'],
            'gender'        => [ 'required'],
            'date_of_birth' => [ 'required'],
            'on_behalf'     => [ 'required'],
            'phone'=> [ 'required'],
            'children'=> [ 'required'],
            'photo'=> [ 'sometimes','mimes:jpeg,jpg,png,gif,webp','image'],
            'marital_status'=> [ 'required'],
        ];
    }

        /**
     * Get the validation messages of rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required'             => translate('First Name is required'),
            'first_name.max'                  => translate('Max 255 characters'),
            'last_name.required'              => translate('First Name is required'),
            'last_name.max'                   => translate('Max 255 characters'),
            'gender.required'                 => translate('Gender is required'),
            'date_of_birth.required'          => translate('Date Of Birth is required'),
            'on_behalf.required'              => translate('On Behalf is required'),
            'marital_status.required'         => translate('Marital Status is required'),
            'phone.required'         => translate('Marital Status is required'),
            'children.required'         => translate('Marital Status is required'),
            'photo.required'         => translate('Marital Status is required'),
        ];
    }
}

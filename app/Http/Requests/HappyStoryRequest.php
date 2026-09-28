<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HappyStoryRequest extends FormRequest
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
            'title'             => ['required', 'max:255'],
            'details'           => ['required'],
            'partner_name'      => ['required', 'max:255'],
            'photos'            => ['required'],
            // 'video_provider'    => ['required'],
            // 'video_link'        => ['required'],
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
            'title.required'              => translate('Story Title is required'),
            'title.max'                   => translate('Max 255 characters'),
            'details.required'            => translate('Story Details is required'),
            'partner_name.required'       => translate('Partner Name is required'),
            'partner_name.max'            => translate('Max 100 characters'),
            'photos.required'             => translate('Photos are required'),
            'video_provider.required'     => translate('video_provider are required'),
            'video_link.required'         => translate('Photos are required'),
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementRequest extends FormRequest
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
        if ($this->route()->getActionMethod() == 'store') {
            return [
                'title' => 'required|max:255',
                'description' => 'required|max:1023',
                'files' => 'required|max:3',
                'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ];
        }

        return [
            'title' => 'required|max:255',
            'description' => 'required|max:1023',
            'files' => 'max:3',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}

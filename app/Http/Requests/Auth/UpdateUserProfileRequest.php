<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . user()->id,
            'phone' => 'required|unique:users,phone,' . user()->id,
            'image' => 'max:255',

        ];
    }

    /**
     * Prepare parameters from Form Request.
     *
     * @return array
     */
    public function parameters()
    {
        return [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $this->input('phone'),
            'image' => $this->input('image'),
        ];
    }

    public function authorize()
    {
        return true;
    }
}

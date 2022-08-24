<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateAdminProfileRequest extends BaseRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')
                    ->whereNot('id', $this->input('id')),
            ],
            'phone' => [
                'required',
                'regex:/(0[1-9])+([0-9]{8})\b/',
                Rule::unique('admins', 'phone')
                    ->whereNot('id', $this->input('id')),
            ],
            'image' => 'required',
        ]);
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
}

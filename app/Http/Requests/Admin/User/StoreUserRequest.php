<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\BaseRequest;
use App\Models\Role;
use Illuminate\Validation\Rule;

class StoreUserRequest extends BaseRequest
{
    public function rules()
    {
        $userRoles = Role::whereGuard('user')->pluck('id');

        return array_merge(parent::rules(), [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
            ],
            'phone' => [
                'max:100',
                Rule::unique('users', 'phone')
            ],
            'image' => 'max:255',

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
            'is_player' => $this->input('is_player'),
        ];
    }

}

<?php

namespace App\Http\Requests\Admin\Bank;

use App\Enums\BankStatus;
use App\Http\Requests\BaseRequest;

class UpdateBankRequest extends BaseRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'username' => 'required|max:255',
            'password' => 'required|max:255',
            'account_number' => 'required|max:255|unique:banks,account_number,' . $this->id,
            'status' => 'required|in:' . implode(',', BankStatus::toArray()),
        ]);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'username' => 'Tên đăng nhập ibank',
            'password' => 'Mật khẩu',
            'account_number' => 'Id tài khoản',
            'status' => 'Trạng thái',
        ]);
    }

    public function parameters()
    {
        return [
            'username' => $this->input('username'),
            'password' => $this->input('password'),
            'account_number' => $this->input('account_number'),
            'status' => $this->input('status'),
            'imei' => $this->input('auto_imei') ? generateImei() : $this->imei,
            'token' => $this->input('auto_token') ? generateToken() : $this->token
        ];
    }
}

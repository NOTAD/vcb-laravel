<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;
use App\Rules\ValidateSignTransfer;

class CreateTransferRequest extends BaseRequest
{
    /**
     * @var mixed
     */

    public function rules()
    {
        return array_merge(parent::rules(), [
            'amount' => 'required',
            'callback_url' => 'required|url|max:255',
            'receiver_account_number' => 'required|max:255',
            'receiver_bank_id' => 'required|max:255',
            'reason' => 'required|max:255',
            'sign' => ['required',
                new ValidateSignTransfer($this->parameters()),
            ]
        ]);
    }

    public function parameters()
    {
        return [
            'amount' => $this->input('amount'),
            'receiver_account_number' => $this->input('receiver_account_number'),
            'reason' => $this->input('reason'),
            'receiver_bank_id' => $this->input('receiver_bank_id'),
            'callback_url' => $this->input('callback_url'),
            'sign' => $this->input('sign')
        ];
    }

    public function attributes()
    {
        return [
            'amount' => 'số tiền',
            'receiver_account_number' => 'số tài khoản nhận tiền',
            'receiver_bank_id' => 'mã ngân hàng nhận tiền',
            'reason' => 'nội dung chuyển khoản',
            'sign' => 'Chữ ký'
        ];
    }

}

<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;
use App\Rules\ValidateSignConfirmOTP;

class ConfirmOtpTransferRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'trans_id' => 'required|exists:history_transfers,trans_id',
            'system_id' => 'required|exists:history_transfers,system_id',
            'otp' => 'required',
            'sign' => [
                'required',
                new ValidateSignConfirmOTP($this->parameters())
            ]
        ]);
    }

    public function parameters()
    {
        return [
            'trans_id' => $this->input('trans_id'),
            'system_id' => $this->input('system_id'),
            'otp' => $this->input('otp'),
            'sign' => $this->input('sign'),
        ];
    }
}

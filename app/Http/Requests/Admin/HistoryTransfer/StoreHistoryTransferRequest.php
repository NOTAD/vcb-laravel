<?php

namespace App\Http\Requests\Admin\HistoryTransfer;

use App\Enums\TransactionStatus;
use App\Http\Requests\BaseRequest;

class StoreHistoryTransferRequest extends BaseRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'amount' => 'required|numeric',
            'receiver_account' => 'required|max:255',
            'receiver_bank_id' => 'required|max:255',
            'reason' => 'required|max:255',
            'trans_id' => 'required|max:255',
            'system_id' => 'required|max:255',
            'status' => 'required|in:' . implode(',', TransactionStatus::toArray()),
            'bank_id' => 'required|exists:banks,id'
        ]);
    }

}

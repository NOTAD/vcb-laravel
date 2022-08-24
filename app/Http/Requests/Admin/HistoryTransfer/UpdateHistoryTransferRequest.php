<?php

namespace App\Http\Requests\Admin\HistoryTransfer;

use App\Enums\TransactionStatus;
use App\Http\Requests\BaseRequest;

class UpdateHistoryTransferRequest extends BaseRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'status' => 'required|in:' . implode(',', TransactionStatus::toArray()),
            'message' => 'required'
        ]);
    }

}

<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;

class HandleTransfer extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'trans_id' => 'required|exists:history_transfers,trans_id'
        ]);
    }

    public function parameters()
    {
        return [
            'trans_id' => $this->input('trans_id')
        ]; // TODO: Change the autogenerated stub
    }
}

<?php

namespace App\Http\Requests\File;

use App\Http\Requests\BaseRequest;

class DeleteFileRequest extends BaseRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'files' => 'required',
        ]);
    }

    /**
     * Prepare parameters from Form Request.
     *
     * @return array
     */
    public function parameters()
    {
        return array_merge(parent::parameters(), [
            'files' => $this->file('files'),
        ]);
    }

}

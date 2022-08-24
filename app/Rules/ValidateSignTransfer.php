<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateSignTransfer implements Rule
{
    protected $params;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value === md5($this->params['amount'] . $this->params['reason'] . $this->params['receiver_account_number'] . $this->params['receiver_bank_id'] . $this->params['callback_url']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Chữ ký không hợp lệ.';
    }
}

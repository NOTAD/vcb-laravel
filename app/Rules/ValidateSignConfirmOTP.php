<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateSignConfirmOTP implements Rule
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
        return $value === md5((string)$this->params['trans_id'] . (string)$this->params['system_id'] . (string)$this->params['otp']);
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

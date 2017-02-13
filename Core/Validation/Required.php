<?php
namespace Core\Validation;

use Core\Validation\Validation;

class Required extends Validation
{
    const MESSAGE = 'is required and can not be empty';

    /**
     * @param mixed $value
     * $options array $options
     * @return bool
     */
    public function validate($value, $options = [])
    {
        if (!$value) {
            $this->setErrorMessage(self::MESSAGE);
            return false;
        } else {
            return true;
        }
    }
}
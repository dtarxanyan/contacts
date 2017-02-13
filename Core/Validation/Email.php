<?php
namespace Core\Validation;

class Email extends Validation
{
    const MESSAGE = 'is not a valid email';

    /**
     * @param $value
     * @param array $options
     * @return bool
     */
    public function validate($value, $options = [])
    {
        if (!$value) {
            return true;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL) === true) {
            $this->setErrorMessage(self::MESSAGE);
            return false;
        } else {
            return true;
        }
    }

}
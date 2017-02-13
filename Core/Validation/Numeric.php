<?php
namespace Core\Validation;

use Core\Validation\Validation;

class Numeric extends Validation
{
    const ERROR_ONLY_INT = 'shall contain only integer';
    const ERROR_ONLY_NUMBERS = 'shall contain only numbers';

    const OPTION_INTEGER_ONLY = 'integerOnly';

    /**
     * @param mixed $value
     * @param array $options
     * @return bool
     */
    public function validate($value, $options = [])
    {
        if (!$value) {
            return true;
        }

        $result = false;

        if (isset($options[self::OPTION_INTEGER_ONLY])) {
            if (!is_int($value)) {
                $this->setErrorMessage(self::ERROR_ONLY_INT);
            } else {
                $result = true;
            }
        } else {
            if (!is_numeric($value)) {
                $this->setErrorMessage(self::ERROR_ONLY_NUMBERS);
            } else {
                $result = true;
            }
        }

        return $result;
    }
}
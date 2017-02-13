<?php
namespace Core\Validation;

use Core\Validation\Validation;

class Alphabetic extends Validation
{
    const ERROR_LETTERS_AND_NUMBERS = 'shall contain only letters and numbers';
    const ERROR_ONLY_LETTERS = 'shall contain only letters';

    const OPTION_ONLY_LETTERS = 'lettersOnly';

    /**
     * @param string $value
     * @param array $options
     * @return bool
     */
    public function validate($value, $options = [])
    {
        if (!$value) {
            return true;
        }

        if (isset($options[self::OPTION_ONLY_LETTERS])) {
            $pattern = '/^[A-Za-z]+$/';
            $errorMessage = self::ERROR_ONLY_LETTERS;
        } else {
            $pattern = '/^[A-Za-z0-9]+$/';
            $errorMessage = self::ERROR_LETTERS_AND_NUMBERS;
        }

        if (preg_match($pattern, $value)) {
            return true;
        } else {
            $this->setErrorMessage($errorMessage);
            return false;
        }
    }
}
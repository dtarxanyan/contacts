<?php

namespace Core\Validation;

abstract class Validation
{
    private $errorMessage;

    abstract protected function validate($value, $options = []);

    /**
     * @return mixed
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    protected function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }
}
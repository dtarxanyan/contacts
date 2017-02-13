<?php
namespace Core\Validation;

interface ValidationInterface
{
    function validate($value, $options = []);
    function getErrorMessage();
    function setErrorMessage();
}
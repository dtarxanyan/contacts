<?php

namespace Core\Helpers;

use Core\BaseModel;
use Core\Helpers\Html;

class Form
{
    /**
     * @param BaseModel $model
     * @param string $attribute
     * @param array $htmlOptions
     * @param array $labelHtmlOptions
     * @return string
     */
    public static function inputField(BaseModel $model, $attribute, $htmlOptions = [], $labelHtmlOptions = [])
    {
        return Html::label($model, $attribute, $labelHtmlOptions)
            . Html::inputField($model, $attribute, $htmlOptions)
            . Html::error($model, $attribute);
    }

    /**
     * @param aray $htmlOptions
     * @return string string
     */
    public static function button($htmlOptions)
    {
        return Html::openTag('input', $htmlOptions);
    }
}
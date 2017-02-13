<?php

namespace Core\Helpers;

use Core\BaseModel;

class Html
{
    /**
     * @param BaseModel $model
     * @param string $attribute
     * @param array $htmlOptions
     * @return string
     */
    public static function inputField(BaseModel $model, $attribute, $htmlOptions = [])
    {
        if (!isset($htmlOptions['value'])) {
            $htmlOptions['value'] = $model->$attribute;
        }

        if (!isset($htmlOptions['name'])) {
            $htmlOptions['name'] = self::createHtmlName($model, $attribute);
        }

        return self::openTag('input', $htmlOptions);
    }

    /**
     * @param BaseModel $model
     * @param string $attribute
     * @param array $htmlOptions
     * @return string
     */
    public static function label(BaseModel $model, $attribute, $htmlOptions = [])
    {
        if (!isset($htmlOptions['for'])) {
            $htmlOptions['for'] = self::createHtmlId($model, $attribute);
        }

        return self::openTag('label', $htmlOptions)
            . $model->getLabel($attribute)
            . self::closeTag('label');
    }

    /**
     * @param BaseModel $model
     * @param string $attribute
     * @param array $htmlOptions
     * @return string
     */
    public static function error(BaseModel $model, $attribute, $htmlOptions = [])
    {
        return self::openTag('span', $htmlOptions)
            . $model->getError($attribute)
            . self::closeTag('span');
    }

    /**
     * @param string $name
     * @param array $htmlOptions
     * @return string
     */
    public static function openTag($name, $htmlOptions = [])
    {
        return '<'
            . strtolower($name)
            . ' '
            . self::createHtmlOptions($htmlOptions)
            . '>';
    }

    /**
     * @param string $name
     * @return string
     */
    public static function closeTag($name)
    {
        return '</' . strtolower($name) . '>';
    }

    /**
     * @param array $options
     * @return string
     */
    private static function createHtmlOptions(array $options)
    {
        $optionsString = "";

        foreach ($options as $attribute => $value) {
            $optionsString .= "$attribute=\"$value\" ";
        }

        return $optionsString;
    }

    /**
     * @param BaseModel $model
     * @param string $attribute
     * @return string
     */
    private static function createHtmlId(BaseModel $model, $attribute)
    {

        return self::getPureClassName($model) . '_' . $attribute;
    }

    /**
     * @param BaseModel $model
     * @param string $attribute
     * @return string
     */
    private function createHtmlName(BaseModel $model, $attribute)
    {
        return self::getPureClassName($model) . "[$attribute]";
    }

    /**
     * @param BaseModel $model
     * @return string
     */
    private function getPureClassName(BaseModel $model)
    {
        $path = explode('\\', get_class($model));
        return array_pop($path);
    }
}
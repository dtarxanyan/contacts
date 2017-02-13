<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2/12/2017
 * Time: 10:23 PM
 */

namespace Core\Validation;

use Core\Validation\Validation;

class File extends Validation
{
    const OPTION_ALLOWED_EXT = 'ext';
    const OPTION_ALLOWED_TYPES = 'mime';
    const OPTION_MAX_SIZE = 'size';

    const ERROR_INVALID_EXT = 'Invalid file extension';
    const ERROR_INVALID_TYPE = 'Invalid file type';
    const ERROR_INVALID_SIZE = 'Invalid file size';


    public function validate($file, $options = [])
    {
        $fileInfo = new \SplFileInfo($file);


        if (isset($options[self::OPTION_ALLOWED_EXT])) {
            $allowedExt = $options[self::OPTION_ALLOWED_EXT];
            $fileExt = $fileInfo->getExtension();

            if (!in_array($fileExt, $allowedExt)) {
                $this->setErrorMessage(self::ERROR_INVALID_EXT);
                return false;
            }
        }

        if (isset($options[self::OPTION_ALLOWED_TYPES])) {
            $allowedTypes = $options[self::OPTION_ALLOWED_TYPES];
            $mimeType = mime_content_type($file);

            if (!in_array($mimeType, $allowedTypes)) {
                $this->setErrorMessage(self::ERROR_INVALID_TYPE);
                return false;
            }
        }

        if (isset($options[self::OPTION_MAX_SIZE])) {
            $maxSize = $options[self::OPTION_MAX_SIZE] * 1000000;
            $fileSize = $fileInfo->getSize();

            if ($fileSize >= $maxSize) {
                $this->setErrorMessage(self::ERROR_INVALID_SIZE);
                return false;
            }
        }

        return true;
    }

}
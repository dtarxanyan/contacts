<?php
namespace Core;

abstract class BaseController
{
    const DEFAULT_LAYOUT = 'main';

    /**
     * @var string
     */
    private $layout;

    /**
     * string
     */
    private $baseUrl;

    /**
     * string
     */
    private $uploadsFolder;


    public function __construct()
    {
        $this->setLayout(self::DEFAULT_LAYOUT);
        $this->setUploadsFolder(UPLOAD_PATH);
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param mixed $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return mixed
     */
    public function getUploadsFolder()
    {
        return $this->uploadsFolder;
    }

    /**
     * @param mixed $uploadsFolder
     */
    public function setUploadsFolder($uploadsFolder)
    {
        $this->uploadsFolder = $uploadsFolder;
    }

    protected function redirect($route)
    {
        header('Location: ' . $this->getBaseUrl() . $route);
    }
}
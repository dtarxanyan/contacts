<?php
namespace Core\Response;

class HtmlResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $viewsDirectory;

    /**
     * @var string
     */
    private $layout;
    /**
     * @var string
     */
    private $view;

    private $pageSpecificCss;
    private $pageSpecificJs;


    /**
     * @var string $assetsPath
     */
    private $assetsPath;

    /**
     * @var string $baseUrl
     */
    private $baseUrl;

    public function __construct($content = [])
    {
        $this->setViewsDirectory(__DIR__ . '/../../App/views/');
        $this->pageSpecificCss = [];
        $this->pageSpecificJs = [];
        parent::__construct($content);
    }

    public function render()
    {
        $this->checkFilesExist();
        extract($this->getContent());
        $this->setAssetsPath($this->getBaseUrl() . 'assets/');
        ob_start();
        $viewContent = require $this->getView();
        $viewContent = ob_get_contents();
        ob_end_clean();

        require $this->getLayout();


    }

    /**
     * @throws \Exception
     */
    private function checkFilesExist()
    {
        if (!file_exists($this->getLayout())) {
            throw new \Exception('Layout file does not exist: ' . $this->getLayout());
        }

        if (!file_exists($this->getView())) {
            throw new \Exception('View file does not exist: ' . $this->getView());
        }

    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $this->getViewsDirectory() . 'layouts/' . $layout . '.php';
        return $this;
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param $view
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $this->getViewsDirectory() . $view . '.php';
        return $this;

    }

    /**
     * @return string
     */
    public function getViewsDirectory()
    {
        return $this->viewsDirectory;
    }

    /**
     * @param string $viewsDirectory
     */
    public function setViewsDirectory($viewsDirectory)
    {
        $this->viewsDirectory = $viewsDirectory;
    }

    /**
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->assetsPath;
    }

    /**
     * @param $assetsPath
     * @return $this
     */
    public function setAssetsPath($assetsPath)
    {
        $this->assetsPath = $assetsPath;
        return $this;
    }

    /**
     * @param string $css
     * @return $this
     */
    private function addPageSpecificCss($css)
    {
        array_push($this->pageSpecificCss, $css);
        return $this;
    }

    /**
     * @param string $js
     * @return $this
     */
    private function addPageSpecificJs($js)
    {
        array_push($this->pageSpecificJs, $js);
        return $this;
    }

    /**
     * @return array
     */
    public function getPageSpecificCss()
    {
        return $this->pageSpecificCss;
    }

    /**
     * @return array
     */
    public function getPageSpecificJs()
    {
        return $this->pageSpecificJs;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }
}
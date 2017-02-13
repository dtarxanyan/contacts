<?php
namespace Core;

use App\Controllers\Index;
use Core\Exception\RouteNotFoundException;

class Router
{
    /**
     * @var string
     */
    private $requestUri;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    const DEFAULT_CONTROLLER = 'site';
    const DEFAULT_ACTION = 'login';

    public function __construct()
    {
        $this->setRequestUri(ltrim($_SERVER['REQUEST_URI'], '/'));
    }

    /**
     * @throws \Exception
     */
    public function dispatch()
    {
        if ($this->getRequestUri() == '' || $this->getRequestUri() == '/') {
            $this->setController(self::DEFAULT_CONTROLLER);
            $this->setAction(self::DEFAULT_ACTION);
        } else {
            $temp = explode('?', $this->getRequestUri());
            $temp = explode('/', $temp[0]);
            $this->setController($temp[0]);
            if (empty($temp[1]) || $temp[1] == '/') {
                $this->setAction(self::DEFAULT_ACTION);
            } else {
                $this->setAction($temp[1]);
            }
        }

        $this->validate();
    }

    /**
     * @throws \Exception
     */
    private function validate()
    {
//        if (!file_exists(__DIR__ . '/../App/Controllers/' . $this->getController() . '.php')) {
//            throw new RouteNotFoundException();
//        }
//
//        if (!class_exists("\App\\Controllers\\" . $this->getController())) {
//           // throw new RouteNotFoundException();
//            var_dump(new Index());
//            die;
//        }
//
//        if (!method_exists("App\\Controllers\\" . $this->getController(), $this->getAction())) {
//            throw new RouteNotFoundException('Method ' . "App\\Controllers\\" . $this->getController() . '::' . $this->getAction() );
//        }
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return ucfirst($this->action);
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return ucfirst($this->controller);
    }

    /**
     * @param string $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * @param string $requestUri
     */
    public function setRequestUri($requestUri)
    {
        $this->requestUri = $requestUri;
    }
}
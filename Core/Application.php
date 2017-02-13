<?php
namespace Core;

use Core\Response\AbstractResponse;
use Core\Response\HtmlResponse;
use Core\Exception\RouteNotFoundException;


class Application {
    private static $config;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $baseUrl;

    CONST EXCEPTIONS_CONTROLLER = 'exceptions';
    CONST EXCEPTION_ACTION = 'exception';
    CONST EXCEPTION_NOT_FOUND_ACTION = 'page404';

    public static function setConfig()
    {
        self::$config = require __DIR__ . "/../App/config.php";
    }

    /**
     * @param bool|string $key
     * @return array|string
     * @throws \Exception
     */
    public static function getConfig($key = false)
    {
        if ($key === false) {
            return self::$config;
        }
        if (array_key_exists($key, self::$config)) {
            return self::$config[$key];
        }
        throw new \Exception('Configuration with given key does not exist');

    }
    public function __construct()
    {
        self::setConfig();
        $this->setRouter(new Router());
        $this->setBaseUrl($this->getConfig('base_url'));
    }

    public function start()
    {
        // TODO: create session component
        session_start();

        try {
            /**
             * @var  \Core\BaseController $controllerInstance
             */
            $this->router->dispatch();
            $controllerName = "App\\Controllers\\"  .$this->router->getController();
            $controllerInstance = new $controllerName();
            $controllerInstance->setBaseUrl($this->getBaseUrl());
            $respObj = call_user_func([$controllerInstance, $this->router->getAction()]);
            $this->checkAndRenderView($respObj, $controllerInstance);
        } catch (RouteNotFoundException $e) {
            $this->redirect(self::EXCEPTIONS_CONTROLLER, self::EXCEPTION_NOT_FOUND_ACTION);
        }
        catch (\Exception $e) {
           $this->handleException($e);
        }

    }

    private function redirect($controller = Router::DEFAULT_CONTROLLER, $action = Router::DEFAULT_ACTION)
    {
        header('Location: ' . $this->getBaseUrl() . $controller . '/' . $action);
    }

    /**
     * @param AbstractResponse $respObj
     * @param BaseController $controllerInstance
     * @throws \Exception
     */
    private function checkAndRenderView(AbstractResponse $respObj, BaseController $controllerInstance)
    {
        if (!$respObj instanceof AbstractResponse) {
            throw new \Exception('Controller has not returned an instance of acceptable view model');
        }

        if ($respObj instanceof HtmlResponse) {
            $respObj
                ->setLayout(strtolower($controllerInstance->getLayout()))
                ->setView(strtolower($this->router->getController()) . '/' . strtolower($this->router->getAction()))
                ->setBaseUrl($this->getBaseUrl())
            ;
        }

        $respObj->render();
    }


    private function handleException(\Exception $e)
    {
        $respObj = new HtmlResponse(['exception' => $e]);
        $respObj->setLayout(BaseController::DEFAULT_LAYOUT)
            ->setView(self::EXCEPTIONS_CONTROLLER . '/' . self::EXCEPTION_ACTION)
            ->setBaseUrl($this->getBaseUrl());
        $respObj->render();
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }


}
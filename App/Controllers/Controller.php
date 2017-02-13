<?php
namespace App\Controllers;


use Core\BaseController;

abstract class Controller extends BaseController
{
    /**
     * @var array
     */
    protected $guestActions = [];

    /**
     * @param string $action
     */
    protected function checkLoginStatus($action)
    {
        $isGuest = true;

        if (isset($_SESSION['login']) && $_SESSION['login']) {
            $isGuest = false;
        }

        if ($isGuest && !in_array($action, $this->guestActions)) {
            $this->redirect("site/login");
        }
    }

    /**
     * @param $name
     * @param mixed $default
     * @param bool $returnDefaultEmpty
     * @return mixed
     */
    protected function getPost($name, $default = null, $returnDefaultIfEmpty = true)
    {
        $value = isset($_POST[$name]) ? $_POST[$name] : null;

        if (!$value && $returnDefaultIfEmpty) {
            $value = null;
        }

        return isset($value) ? $value : $default;
    }
}
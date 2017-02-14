<?php
namespace App\Controllers;

use Core\Response\HtmlResponse;
use Core\BaseController;


class Exceptions extends BaseController
{
    /**
     * Render 404 page
     * @return HtmlResponse
     */
    public function page404()
    {
        return new HtmlResponse();
    }

    /**
     * Render errors
     * @return HtmlResponse
     */
    public function error()
    {
        return new HtmlResponse();
    }
}
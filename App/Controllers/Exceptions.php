<?php
namespace App\Controllers;

use Core\Response\HtmlResponse;
use Core\BaseController;


class Exceptions extends BaseController
{
    public function page404() {
        return new HtmlResponse();
    }

    public function error(){
        //return new HtmlResponse();
    }
}
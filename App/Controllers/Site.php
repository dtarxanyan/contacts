<?php
namespace App\Controllers;

use App\Models\LoginForm;
use Core\Response\HtmlResponse;

class Site extends Controller
{
    /**
     * @var array
     */
    protected $guestActions = [
        'login'
    ];

    /**
     * User login
     * @return HtmlResponse
     */
    public function login()
    {
        $model = new LoginForm();

        if (isset($_POST['LoginForm'])) {
            $model->email = $_POST['LoginForm']['email'];
            $model->password = $_POST['LoginForm']['password'];

            if ($model->login()) {
                $_SESSION['login'] = 1;
                $_SESSION['email'] = $model->email;
                $this->redirect('contacts/list');
            }
        }

        return new HtmlResponse([
            'model' => $model,
        ]);
    }

    /**
     * User logout
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('site/login');
    }
}
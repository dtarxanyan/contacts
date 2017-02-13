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

    public function login()
    {
        $model = new LoginForm();

        if (isset($_POST['LoginForm'])) {
            $model->email = $_POST['LoginForm']['email'];
            $model->password = $_POST['LoginForm']['password'];

            if ($model->login()) {
                // TODO: create session component
                $_SESSION['login'] = 1;
                $_SESSION['email'] = $model->email;
                $this->redirect('contacts/list');
            }
        }

        return new HtmlResponse([
            'model' => $model,
        ]);
    }

    public function logout()
    {
        // TODO: create session component
        unset($_SESSION['login']);
        unset($_SESSION['email']);
        $this->redirect('site/login');
    }
}
<?php
namespace App\Models;

use Core\BaseModel;
use Core\Helpers\Hash;

class LoginForm extends BaseModel
{
    const TABLE_NAME = 'users';
    const ERROR_LOGIN_FAILED = 'Invalid username or password';

    /**
     * @var array
     */
    public $attributes = [
        'email' => [
            'name' => 'Email',
            'value' => '',
            'rules' => ['required' => []]
        ],
        'password' => [
            'name' => 'Password',
            'value' => '',
            'rules' => ['required' => []],
        ]
    ];


    /**
     * @return bool|int
     */
    public function login()
    {
        $result = false;

        if ($this->validate()) {
            $sql = "SELECT id, password"
                . "  FROM " . self::TABLE_NAME
                . " WHERE email=:email";

            $data = $this->find($sql, ['email' => $this->email], \PDO::FETCH_ASSOC);


            if ($data) {
                $hashPassword = $data['password'];
                if (Hash::compare($this->password, $hashPassword)) {
                    $result = $data['id'];
                }
            }

            if (!$result) {
                $this->setError('password', self::ERROR_LOGIN_FAILED);
            }

            return $result;

        }

        return $result;
    }
}
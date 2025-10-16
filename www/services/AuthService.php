<?php

namespace app\services;

use app\core\Application;
use app\core\SecureSession;
use app\core\security\PasswordHashUtility;
use app\models\User;

class AuthService
{
    private User $model;
    private $user;
    private $sessionName;

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->sessionName = SESSION_NAME;
    }

    public function authenticate(string $email, string $password): bool
    {
        try {
            $this->user = $this->model->findWhere(['email' => $email]);
            if ($this->user) {
                $hash = $this->user->password_hash;
                $verifiedPassword = PasswordHashUtility::create()->verifyPassword($password, $hash);
                // var_dump($hash, $email, $password, $verifiedPassword);
                return $verifiedPassword;
            }
            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function authorize()
    {
        SecureSession::make($this->sessionName)->start();
        $request = Application::$app->request;
        $session = $request->session();
        $session->put('userId', $this->user->id);
        $session->put('userRole', $this->user->role()->name);
    }
}

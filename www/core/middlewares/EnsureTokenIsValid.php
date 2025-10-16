<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\SecureSession;

class EnsureTokenIsValid implements Middleware
{
    public function handle($request, \Closure $next)
    {
        $myRequest = Application::$app->request;
        $secureSession = SecureSession::make(SESSION_NAME)->start();
        $session = $myRequest->session();
        $isUserId = $session->has('userId');
        $isUserRole = $session->has('userRole');

        if ($isUserId && $isUserRole) {
            return $next($request);
        } else {
            // Si el usuario no está autenticado, redirigir a la página de inicio de sesión
            header("Location: " . APP_DIRECTORY_PATH . "/");
            exit;
        }
    }
}

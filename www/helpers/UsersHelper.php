<?php

namespace app\helpers;

use app\core\http\Request;
use app\models\User;

class UsersHelper
{
    private string $nombre;
    private static $instances = [];
    public $role;
    public $id;
    private $usuario;
    protected function __construct(Request $request)
    {
        $session = $request->session();
        $this->role = $session->get('userRole');
        $this->id = $session->get('userId');
        $model = new User();
        $this->usuario = $model->find($this->id);
    }
    public static function create(Request $request)
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static($request);
        }
        return self::$instances[$className];
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return string
     */
    public function getUserName(): string
    {
        return $this->usuario->first_name . ' ' . $this->usuario->last_name . '<br><strong>' . $this->role . '</strong>';
    }
    public function getAvatar(): string
    {
        return $this->usuario->avatar !== null ? $this->usuario->avatar : 'medical-team.png';
    }
}

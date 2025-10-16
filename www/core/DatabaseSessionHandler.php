<?php

namespace core;

class DatabaseSessionHandler implements SessionHandler
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function open($savePath, $sessionName): bool
    {
        return true;
    }
    public function close()
    {
        return true;
    }
    public function read($id)
    {
    }
    public function write($id, $data)
    {
    }
    public function destroy($id)
    {
    }
    public function gc($lifetime)
    {
    }
}
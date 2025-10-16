<?php

namespace app\core\database;

interface Migration
{
    public function up();
    public function down();
}

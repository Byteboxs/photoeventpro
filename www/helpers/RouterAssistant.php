<?php

namespace app\helpers;

interface RouterAssistant
{
    public function search($route);
    public function getRoute($router, $route);
    public function hasRoute($route);
}

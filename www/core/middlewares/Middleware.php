<?php

namespace app\core\middlewares;

interface Middleware
{
    public function handle($request, \Closure $next);
}

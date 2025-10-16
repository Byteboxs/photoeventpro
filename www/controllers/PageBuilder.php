<?php

namespace app\controllers;

interface PageBuilder
{
    public function initBreadcrumb(array $args = []);
    public function initContent(array $args = []);
    public function initView(array $args = []);
    public function draw(...$args);
}

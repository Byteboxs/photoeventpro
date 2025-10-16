<?php

namespace app\services\ui\breadcrumb;

interface BreadcrumbItem
{
    public function render(): string;
}

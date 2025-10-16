<?php

namespace app\core;

use app\services\ui\form\Form;

interface IFormView
{
    public function getForm(array $args = []): Form;
}

<?php

namespace app\core\model;

interface Approver
{
    public function validate($value);
    public function getErrorMessage();
}

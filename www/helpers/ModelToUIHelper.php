<?php

namespace app\helpers;

use app\core\Singleton;

class ModelToUIHelper extends Singleton
{
    public function formatDataForSelect($data, string $idName, string $name): array
    {
        if (empty($data)) {
            return [];
        }
        if (!is_array($data)) {
            return [$data->{$idName} => $data->{$name}];
        }

        $out = [];
        foreach ($data as $model) {
            $out[$model->{$idName}] = $model->{$name};
        }
        return $out;
    }
}

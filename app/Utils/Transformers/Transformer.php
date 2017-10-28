<?php

namespace App\Utils\Transformers;

abstract class Transformer
{

    public function transformCollection(array $items, $withKeys = false)
    {
        if ($withKeys)
        {
            return array_map([$this, 'transformWithKey'], array_keys($items), $items);
        }

        return array_map([$this, 'transform'], $items);
    }

    public abstract function transform($item);

    public function transformWithKey($key, $item)
    {
        return [$key => $this->transform($item)];
    }
}
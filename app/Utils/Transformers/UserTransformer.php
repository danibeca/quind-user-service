<?php

namespace App\Utils\Transformers;


class UserTransformer extends Transformer
{

    public function transform($indicator)
    {
        return [
            'id'      => $indicator['id'],
            'name'    => $indicator['name'],
            'email'   => $indicator['name'],
            'role_id' => $indicator['roles'][0]['id']
        ];
    }
}
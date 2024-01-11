<?php

namespace Origami\Trait;

trait GetByIdTrait
{
    public function fetchById(string $id)
    {
        return $this->getList([
            'entity_data_name' => $this->entity,
            'filter' => [
                ['_id', '=', $id]
            ]
        ])->json();
    }
}

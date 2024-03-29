<?php

namespace Origami;

use Origami\Dto\Apartment\ApartmentDto;
use Origami\Trait\FieldsKeyValueTrait;
use Origami\Trait\GetByIdTrait;

class Apartment extends BaseOrigami
{
    use FieldsKeyValueTrait, GetByIdTrait;
    private string $entity = 'portal_apartment';

    public function getById(string $id): ?ApartmentDto
    {
        $data = $this->fetchById($id);

        if (empty($data['data'])) {
            return null;
        }

        return ApartmentDto::fromArray($this->getFieldsKeyValue($data));
    }

    public function create(ApartmentDto $dto): ApartmentDto
    {
        $result = $this->store($this->entity, $dto->toArray());
        $dto->apartmentId = $result->id;

        return $dto;
    }

}

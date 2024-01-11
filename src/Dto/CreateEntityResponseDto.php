<?php

namespace Origami\Dto;

use Origami\Dto\Traits\DtoArrayable;


/**
 * @OA\Schema(
 *     schema="CreateEntityResponse",
 *     type="object",
 *     @OA\Property(property="id", type="string"),
 *     @OA\Property(property="success", type="string")
 * )
 */
class CreateEntityResponseDto
{
    use DtoArrayable;
    public function __construct(
        public readonly string $success,
        public readonly string $id
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new self($data['success'], $data['results']['_id']);
    }
}

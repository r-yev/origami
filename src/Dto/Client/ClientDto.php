<?php

namespace App\Service\Origami\Dto\Client;

use App\Service\Origami\Dto\Traits\DtoArrayable;
use App\Service\Origami\Dto\Traits\DtoExArrayable;

class ClientDto
{
    use DtoExArrayable, DtoArrayable;

    public function __construct(
        public string $name,
        public string $phone,
        public readonly ?string $id = null,
        public ?string $email = null,
        public ?string $idNumber = null,
        public ?string $gender = null,
        public ?string $smsCode = null,
        public ?int $smsValid = null,
        public ?string $token = null,
        public ?int $tokenValid = null,
        public int $isPrimary = 0,
        public readonly int $approveToSave = 1
    ) {
    }
}

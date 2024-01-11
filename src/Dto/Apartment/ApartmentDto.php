<?php

namespace Origami\Dto\Apartment;

use Origami\Dto\Traits\DtoArrayable;
use Origami\Dto\Traits\DtoExArrayable;

class ApartmentDto
{
    use DtoExArrayable, DtoArrayable;
    public function __construct(
        public string $apartmentType,
        public string $artNumber,
        public string $address,
        public string $city,
        public string $postalCode,
        public float $price,
        public ?string $clientApartmentId = null,
        public int $locked = 0,
        public ?string $startRent = null,
        public ?string $endRent = null,
        public ?string $firstPaymentDate = null,
        public ?string $apartmentId = null,
    ) {
    }
}

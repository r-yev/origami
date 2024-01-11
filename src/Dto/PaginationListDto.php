<?php

namespace App\Service\Origami\Dto;

class PaginationListDto
{
    public function __construct(
        public int $totalItems,
        public int $prePage,
        public int $currentPage,
        public int $totalPages,
        public ?array $items = []
    ) {
    }
}

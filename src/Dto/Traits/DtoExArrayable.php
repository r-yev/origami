<?php

namespace App\Service\Origami\Dto\Traits;

use Illuminate\Support\Str;

/**
 * @method __construct(...$properties)
 */
trait DtoExArrayable
{
    /**
     * Get the instance from an array.
     *
     * @param  array  $data
     *
     * @return $this
     */
    public static function fromArray(array $data): static
    {
        $props = [];
        foreach ($data as $key => $value) {
            $props[Str::camel($key)] = $value;
        }
        return new static(...$props);
    }
}

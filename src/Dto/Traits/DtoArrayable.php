<?php

namespace Origami\Dto\Traits;

use Illuminate\Support\Str;

trait DtoArrayable
{
    public function toArray(): array
    {
        $newArr = [];
        foreach (get_object_vars($this) as $key => $val) {
            $newArr[Str::snake($key)] = match (true) {
                method_exists($this, self::methodGet($key)) => $this->{self::methodGet($key)}(),
                method_exists($this, self::methodIs($key)) => $this->{self::methodIs($key)}(),
                default => $val,
            };
            $newArr[Str::snake($key)] = $val;
        }

        return $newArr;
    }

    private static function methodGet(string $property): string
    {
        return 'get'.Str::studly($property);
    }

    private static function methodIs(string $property): string
    {
        return 'is'.Str::studly($property);
    }
}

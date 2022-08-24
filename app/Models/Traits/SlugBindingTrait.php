<?php

namespace App\Models\Traits;

trait SlugBindingTrait
{
    public function resolveRouteBinding($value, $field = null)
    {
        // Retrieve by id or key.
        return is_numeric($value)
            ? static::where('id', $value)->first()
            : static::where('slug', $value)->first();
    }
}

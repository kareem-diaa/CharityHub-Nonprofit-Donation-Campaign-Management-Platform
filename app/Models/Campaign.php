<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    /**
     * Get the route key for the model.
     * Uses 'slug' instead of 'id' for clean, SEO-friendly URLs.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

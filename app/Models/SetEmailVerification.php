<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetEmailVerification extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        return optional(static::where('key', $key)->first())->value ?? $default;
    }

    public static function toggle($key)
    {
        $setting = static::where('key', $key)->first();
        $setting->update(['value' => !$setting->value]);
    }
}

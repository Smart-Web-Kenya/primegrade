<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'cms_settings';

    protected $fillable = ['key', 'value', 'type', 'group'];

    public static function get(string $key, $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOL),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value ?? 'null', true),
            default => $setting->value,
        };
    }

    public static function set(string $key, $value, string $group = 'general'): self
    {
        $type = match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_array($value) => 'json',
            default => 'text',
        };

        $storedValue = is_array($value)
            ? json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            : ($value === null ? null : (string) $value);

        return static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'type' => $type, 'group' => $group],
        );
    }
}

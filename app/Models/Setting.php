<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['group', 'key', 'value', 'type', 'is_public'])]
class Setting extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        $value = $setting->value;
        switch ($setting->type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int)$value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'string', bool $isPublic = true)
    {
        $valStr = is_array($value) || is_object($value) ? json_encode($value) : (string)$value;
        return self::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $valStr,
                'type' => $type,
                'is_public' => $isPublic
            ]
        );
    }
}

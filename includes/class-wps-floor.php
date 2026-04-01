<?php

if (!defined('ABSPATH')) exit;

enum WPS_Floor: int
{
    case BASEMENT = -1;
    case GROUND = 0;
    case FIRST = 1;
    case SECOND = 2;
    case THIRD = 3;

    public function label(): string
    {
        return match($this) {
            self::BASEMENT => 'Sous-sol',
            self::GROUND => 'Rez-de-chaussée',
            self::FIRST => '1er étage',
            self::SECOND => '2ème étage',
            self::THIRD => '3ème étage',
        };
    }

    /**
     * Return an associative array of value => label in enum order
     *
     * @return array<int,string>
     */
    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->label();
        }
        return $out;
    }

    /**
     * Return a label for a raw value, or fallback string if unknown
     */
    public static function labelFor(mixed $value, string $fallback = ''): string
    {
        if ($value === null || $value === '') {
            return $fallback;
        }

        // try int conversion
        $int = is_numeric($value) ? intval($value) : null;
        if ($int === null) {
            return $fallback;
        }

        $enum = self::tryFrom($int);
        return $enum ? $enum->label() : $fallback;
    }
}

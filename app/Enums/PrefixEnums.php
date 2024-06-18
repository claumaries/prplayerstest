<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum PrefixEnums: string
{
    case MR  = 'Mr';
    case MRS = 'Mrs';
    case MS  = 'Ms';

    /**
     * @return array
     */
    public static function keys(): array
    {
        return Arr::pluck(PrefixEnums::cases(), 'name');
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return Arr::pluck(PrefixEnums::cases(), 'value', 'name');
    }

    /**
     * Determine the gender based on the prefix.
     *
     * @return string
     */
    public function gender(): string
    {
        return $this === self::MR ? 'male' : 'female';
    }
}

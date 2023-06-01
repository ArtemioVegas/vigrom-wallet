<?php

declare(strict_types=1);

namespace App\Enum;

enum Reason: string
{
    case STOCK = 'stock';
    case REFUND = 'refund';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isCredit(): bool
    {
        return $this === self::CREDIT;
    }

    public function isDebit(): bool
    {
        return $this === self::DEBIT;
    }
}

<?php

declare(strict_types=1);

namespace App\API;

final class BalanceResponse
{
    public readonly string $currency;
    public readonly int $amountInMinor;

    public function __construct(string $currency, int $amount)
    {
        $this->currency = $currency;
        $this->amountInMinor = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmountInMinor(): int
    {
        return $this->amountInMinor;
    }
}

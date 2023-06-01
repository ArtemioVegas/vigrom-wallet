<?php

declare(strict_types=1);

namespace App\API;

use App\Enum\Currency;
use App\Enum\Reason;
use App\Enum\TransactionType;
use Symfony\Component\Validator\Constraints as Assert;

final class ChangeBalanceCommand
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $walletId;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $amountInMinor;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [Currency::class, 'values'])]
    public string $currency;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [TransactionType::class, 'values'])]
    public string $type;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [Reason::class, 'values'])]
    public string $reason;

    public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function getAmountInMinor(): int
    {
        return $this->amountInMinor;
    }

    public function convertToEnumCurrency(): Currency
    {
        return Currency::from($this->currency);
    }

    public function convertToEnumType(): TransactionType
    {
        return TransactionType::from($this->type);
    }

    public function convertToEnumReason(): Reason
    {
        return Reason::from($this->reason);
    }
}

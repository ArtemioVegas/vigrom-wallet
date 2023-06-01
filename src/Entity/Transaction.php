<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Currency;
use App\Enum\Reason;
use App\Enum\TransactionType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    /** Minor */
    #[ORM\Column(type: 'integer')]
    private int $amount;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    #[ORM\Column(type: 'string', length: 255)]
    private string $reason;

    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private Wallet $wallet;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        Wallet $wallet,
        TransactionType $type,
        Currency $currency,
        Reason $reason,
        int $amount,
        \DateTimeImmutable $createdAt = null
    ) {
        $this->wallet = $wallet;
        $this->type = $type->value;
        $this->currency = $currency->value;
        $this->reason = $reason->value;
        $this->amount = $amount;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    }
}

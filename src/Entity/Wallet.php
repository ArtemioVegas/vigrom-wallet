<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Currency;
use App\Exception\InsufficientFundsException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    /** Minor */
    #[ORM\Column(type: 'integer')]
    private int $amount = 0;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(unique: true, nullable: false)]
    private User $owner;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct(User $owner, Currency $currency, int $amount = 0)
    {
        $this->owner = $owner;
        $this->currency = $currency->value;
        $this->amount = $amount;
        $this->transactions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrency(): Currency
    {
        return Currency::from($this->currency);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function increase(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('amount must be great than zero');
        }

        $this->amount += $amount;
    }

    /**
     * @throws InsufficientFundsException
     */
    public function decrease(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('amount must be great than zero');
        }
        if ($amount > $this->amount) {
            throw new InsufficientFundsException('Insufficient funds');
        }

        $this->amount -= $amount;
    }
}

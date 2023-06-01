<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Wallet;
use App\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class WalletRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Wallet::class);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(int $id): Wallet
    {
        /** @var Wallet $wallet */
        if (!$wallet = $this->repo->find($id)) {
            throw new EntityNotFoundException('Wallet is not found.');
        }
        return $wallet;
    }

    public function add(Wallet $wallet): void
    {
        $this->em->persist($wallet);
    }
}

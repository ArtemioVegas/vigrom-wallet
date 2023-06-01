<?php

declare(strict_types=1);

namespace App\Service;

use App\API\BalanceResponse;
use App\API\ChangeBalanceCommand;
use App\Entity\Transaction;
use App\Repository\WalletRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PessimisticLockException;
use Psr\Log\LoggerInterface;

class WalletService
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
        private readonly ExchangeService $exchangeService
    ){
    }

    public function getBalance(int $walletId): BalanceResponse
    {
        $walletEntity = $this->walletRepository->get($walletId);

        return new BalanceResponse($walletEntity->getCurrency()->value, $walletEntity->getAmount());
    }

    public function changeBalance(ChangeBalanceCommand $command): void
    {
        $wallet = $this->walletRepository->get($command->getWalletId());
        $this->em->beginTransaction();
        try {
            $this->em->lock($wallet, LockMode::PESSIMISTIC_WRITE);

            $type = $command->convertToEnumType();
            $currency = $command->convertToEnumCurrency();
            $reason = $command->convertToEnumReason();
            $amount = $this->exchangeService->convertToCurrency(
                $command->getAmountInMinor(),
                $currency,
                $wallet->getCurrency()
            );

            if ($type->isCredit()) {
                $wallet->decrease($amount);
            } elseif ($type->isDebit()) {
                $wallet->increase($amount);
            } else {
                throw new \DomainException('Unknown transaction type');
            }

            $transaction = new Transaction(
                $wallet,
                $type,
                $currency,
                $reason,
                $amount
            );

            $this->em->persist($wallet);
            $this->em->persist($transaction);
            $this->em->flush();
            $this->em->commit();
        } catch (PessimisticLockException $pessimisticLockException) {
            $this->em->rollback();
            $this->logger->alert('Lock happened!', [$pessimisticLockException, $wallet]);
            throw $pessimisticLockException;
        }
    }
}

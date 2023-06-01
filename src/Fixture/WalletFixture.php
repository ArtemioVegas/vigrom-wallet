<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Wallet;
use App\Enum\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WalletFixture extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $userAlice = $this->getReference(UserFixture::REFERENCE_USER_ALICE);
        $userBob = $this->getReference(UserFixture::REFERENCE_USER_BOB);

        $aliceWallet = new Wallet($userAlice, Currency::RUB, 2500);
        $bobWallet = new Wallet($userBob, Currency::USD, 6600);

        $manager->persist($aliceWallet);
        $manager->persist($bobWallet);
        $manager->flush();
    }
}

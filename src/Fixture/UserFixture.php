<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const REFERENCE_USER_ALICE = 'user_alice';
    public const REFERENCE_USER_BOB = 'user_bob';

    public function load(ObjectManager $manager)
    {
        $alice = new User('alice@gmail.com');
        $manager->persist($alice);
        $this->setReference(self::REFERENCE_USER_ALICE, $alice);

        $bob = new User('bob@mail.com');
        $manager->persist($bob);
        $this->setReference(self::REFERENCE_USER_BOB, $bob);

        $manager->flush();
    }
}

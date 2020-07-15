<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    // Function for create Users's fixtures
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$email, $password])
        {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

            $manager->persist($user);
        }
        $manager->flush();
    }
    // Data to import for the fixtures
    private function getUserData(): array
    {
        return [

            ['have@great.day', '123456'],
            ['email@fortesting.it', '123456'],
            ['test@test.com', '123456']

        ];
    }
}

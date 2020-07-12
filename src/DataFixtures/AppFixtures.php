<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Advert;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $advertisement = new Advert();
            $advertisement->setLabel($faker->sentence);
            $advertisement->setContent($faker->text);
            $advertisement->setViews(rand(10, 2000));
            $manager->persist($advertisement);
        }

        $manager->flush();
    }
}

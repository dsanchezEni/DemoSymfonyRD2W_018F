<?php

namespace App\DataFixtures;

use App\Entity\Trainer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrainerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Initialisation de Faker.
        $faker = \Faker\Factory::create('fr_FR');

        //Créer 20 trainers
        for($i=1;$i<=20;$i++){
            $trainer = new Trainer();
            $trainer->setFirstName($faker->firstName());
            $trainer->setLastName($faker->lastName());
            $trainer->setDateCreated(new \DateTimeImmutable());
            $manager->persist($trainer);
            $this->addReference('trainer'.$i, $trainer);
        }

        $manager->flush();
    }
}

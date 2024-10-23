<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Initialisation de Faker.
        $faker = \Faker\Factory::create('fr_FR');

        $course = new Course();
        //hydrater toutes les propriétés de l'objet Course.
        $course->setName('Symfony');
        $course->setContent('Le développement web côté Serveur (avec Symfony)');
        $course->setDuration(10);
        $course->setDateCreated(new \DateTimeImmutable('2024-10-23'));
        $course->setPublished(true);
        $manager->persist($course);

        $course = new Course();
        //hydrater toutes les propriétés de l'objet Course.
        $course->setName('PHP');
        $course->setContent('Le développement web côté Serveur (avec PHP)');
        $course->setDuration(5);
        $course->setDateCreated(new \DateTimeImmutable('2024-10-10'));
        $course->setPublished(true);
        $manager->persist($course);

        $course = new Course();
        //hydrater toutes les propriétés de l'objet Course.
        $course->setName('Apache');
        $course->setContent('Administration d\'un serveur Apache sous Linux');
        $course->setDuration(5);
        $course->setDateCreated(new \DateTimeImmutable('2024-01-10'));
        $course->setPublished(true);
        $manager->persist($course);

        //Créer 30 cours supplémentaires
        for($i=1;$i<=30;$i++){
            $course = new Course();
            //hydrater toutes les propriétés de l'objet Course.
            $course->setName($faker->word());
            $course->setContent($faker->realText());
            $course->setDuration(mt_rand(1,10));
            $dateCreated=$faker->dateTimeBetween('-2 months','now');
            //DateTimeBetween retourne un dateTime, donc il faut le convertir en DateTimeImmutable
            $course->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
            $dateModified=$faker->dateTimeBetween($course->getDateCreated()->format('Y-m-d'),'now');
            $course->setDateModified(\DateTimeImmutable::createFromMutable($dateModified));
            $course->setPublished(false);
            $manager->persist($course);
        }

        $manager->flush();
    }
}

<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourseControllerTest extends WebTestCase
{
    /*public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }*/

    public function testIfPageCourseExists():void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/cours/');
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();

        //On vérifie le bon titre sur la page
        $this->assertSelectorTextContains('h1', 'Liste des cours');

    }

    public function testValidFromCourses():void{
        $client = static::createClient();
        //On récupére le repository des utilisateurs dans le container
        $userRepository = static::$kernel->getContainer()->get(UserRepository::class);
       //On recherche un utilisateur selon son email ici l'admin
        $adminUser = $userRepository->findOneBy(['email'=>'admin@test.fr']);
        //On simule la connexion
        $client->loginUser($adminUser);

        $crawler = $client->request('GET', '/cours/create');
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        //$this->assertResponseIsSuccessful();
        //On simule la soumission du formulaire.
        $client->submitForm("Ajouter",[
            'course[name]'=>'test',
            'course[content]'=>'description test',
            'course[duration]'=>5,
            'course[categorie]'=>13,
        ]);
        //On vérifie que la soumission a bien été réalisée et qu'il y ait une redirection.
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        //NB: Quand une requete retourne une redirection, le client ne la suit pas automatiquement.
        //On doit forcer la redirection avec followRedirect()
        $client->followRedirect();
        //on vérifie qu'on bien redirigé sur la page détail.
        $this->assertRouteSame('app_cours_show');

        //On vérifie le bon titre sur la page
        $this->assertSelectorTextContains('h1', 'Liste des cours');

    }

    public function testValidFromCoursesWithInvalideName():void{
        $client = static::createClient();
        //On récupére le repository des utilisateurs dans le container
        $userRepository = static::$kernel->getContainer()->get(UserRepository::class);
        //On recherche un utilisateur selon son email ici l'admin
        $adminUser = $userRepository->findOneBy(['email'=>'admin@test.fr']);
        //On simule la connexion
        $client->loginUser($adminUser);

        $crawler = $client->request('GET', '/cours/create');
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        //$this->assertResponseIsSuccessful();
        //On simule la soumission du formulaire.
        $client->submitForm("Ajouter",[
            'course[name]'=>'a',
            'course[content]'=>'description test',
            'course[duration]'=>5,
            'course[categorie]'=>13,
        ]);
        //On vérifie que la soumission a bien été réalisée et qu'il y ait une redirection.
        //Redirection 422 car le nom est invalide mini 2 caractères.
        $this->assertEquals(422, $client->getResponse()->getStatusCode());


    }
}

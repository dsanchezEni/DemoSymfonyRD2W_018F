<?php

namespace App\Tests\Util;

use App\Util\MyService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MyServiceTest extends KernelTestCase
{
    private MyService $myService;

    /**
     * setUp permet d'initialiser le jeu de test entre chaque test unitaire
     * @return void
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->myService = static::getContainer()->get(MyService::class);
    }

    public function testReduceDataWithLength(): void
    {
        //Ligne importante car elle permet de démarrer le Kernel de Symfony.
        //Et ça s'assure que le Kernel est redémarré pour chaque test
        //afin qu'il soit exécuté indépendamment des uns des autres.
      /*  $kernel = self::bootKernel();
        $myService = static::getContainer()->get(MyService::class);*/
        //On teste avec une longueur précise
        $content="Texte de 22 caractères";
        $this->assertSame("Texte d...",$this->myService->reduce($content,10));
    }

    public function testReduceDataWithoutLength(): void
    {
        //Ligne importante car elle permet de démarrer le Kernel de Symfony.
        //Et ça s'assure que le Kernel est redémarré pour chaque test
        //afin qu'il soit exécuté indépendamment des uns des autres.
       /* $kernel = self::bootKernel();
        $myService = static::getContainer()->get(MyService::class);*/
        //On teste avec une longueur précise
        $content="Texte de 22 caractères";
        //Prendra la valeur par défaut 10
        $this->assertSame("Texte d...",$this->myService->reduce($content));
    }
}

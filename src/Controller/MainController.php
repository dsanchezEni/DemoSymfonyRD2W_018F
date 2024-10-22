<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home', methods: ['GET'])]
    public function home(): Response
    {
        //return new Response("<h1>Accueil</h1>");
        return $this->render('main/home.html.twig');
    }

    #[Route('/test', name: 'main_test', methods: ['GET'])]
    public function test(): Response
    {
        //return new Response("<h1>Test</h1>");
        return $this->render('main/test.html.twig');
    }

    #[Route('/test/mon-test', name: 'main_test2', methods: ['GET'])]
    public function test2(): Response
    {
        //return new Response("<h1>Test</h1>");
        return $this->render('main/test2.html.twig');
    }
}

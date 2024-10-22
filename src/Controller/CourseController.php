<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/cours', name: 'app_cours_')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'list',methods: ['GET'])]
    public function list(): Response
    {
        //TODO Rechercher dans la BD la liste de l'ensemble des cours.
        return $this->render('course/list.html.twig',
            []
        );
    }

    #[Route('/{id}', name: 'show',requirements:['id'=>'\d+'],methods: ['GET'])]
    public function show(int $id): Response
    {
        //TODO Rechercher dans la BD le cours en fonction de son ID.
        return $this->render('course/show.html.twig',
            []
        );
    }

    #[Route('/{id}/modifier', name: 'edit',requirements:['id'=>'\d+'],methods: ['GET','POST'])]
    public function edit(int $id): Response
    {
        //TODO Rechercher dans la BD le cours en fonction de son ID pour le mettre à jour.
        return $this->render('course/edit.html.twig',
            []
        );
    }

    #[Route('/create', name: 'create',methods: ['GET','POST'])]
    public function create(): Response
    {
        //TODO Rechercher dans la BD le cours en fonction de son ID pour le mettre à jour.
        return $this->render('course/create.html.twig',
            []
        );
    }
}

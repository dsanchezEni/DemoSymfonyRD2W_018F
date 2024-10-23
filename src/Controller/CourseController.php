<?php

namespace App\Controller;

use App\Entity\Course;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request): Response
    {
        //dd($request);
        dump($request);
        //TODO Rechercher dans la BD le cours en fonction de son ID pour le mettre à jour.
        return $this->render('course/create.html.twig',
            []
        );
    }

    //J'injecte l'entity manager qui s'appelle $em grâce à l'injection de dépendance.
    #[Route('/demo', name: 'demo',methods: ['GET'])]
    public function demo(EntityManagerInterface $em): Response
    {
        //Créer un Objet de type Course et je l'instancie en faisant new
        //Appel implicite au constructeur de l'objet Course.
        $course = new Course();

        //hydrater toutes les propriétés de l'objet Course.
        $course->setName('Symfony');
        $course->setContent('Le développement web côté Serveur (avec Symfony)');
        $course->setDuration(10);
        $course->setDateCreated(new \DateTimeImmutable());
        $course->setPublished(true);

        //Enregistrer dans la base de donnée
        $em->persist($course);
        dump($course);
        //Penser à utiliser le flush sinon l'objet ne sera pas persisté dans la bd.
        $em->flush($course);

        dump($course);

        //On modifie l'objet
        $course->setName('PHP');
        //!Pas besoin de faire le persist car Doctrine connait deja l'objet.
        //Sauvegarde l'objet
        $em->flush($course);
        dump($course);

        //Je supprime mon objet
        $em->remove($course);
        $em->flush();

        return $this->render('course/create.html.twig',
            []
        );
    }
}

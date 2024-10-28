<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
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
    public function list(CourseRepository $courseRepository): Response
    {
        //TODO Rechercher dans la BD la liste de l'ensemble des cours.
        //$allCours = $courseRepository->findAll();
        $allCours = $courseRepository->findBy([],['name'=>'DESC'],5);
        $courses = $courseRepository->findLastCourses();
        return $this->render('course/list.html.twig',
            [
                'courses' => $courses,
                'allCours' => $allCours,
                'tests'=>null
            ]
        );
    }

    #[Route('/{id}', name: 'show',requirements:['id'=>'\d+'],methods: ['GET'])]
    public function show(Course $cours): Response
    {
        //TODO Rechercher dans la BD le cours en fonction de son ID.
        return $this->render('course/show.html.twig',
            ["cours" => $cours]
        );
    }

    #[Route('/{id}/modifier', name: 'edit',requirements:['id'=>'\d+'],methods: ['GET','POST'])]
    public function edit(Course $course,Request $request,EntityManagerInterface $em): Response
    {
        //Ici Symfony associe l'id à l'objet Course c'est comme s'il faisait une requete avec le find en lui passant le parametre id.
        $courseForm = $this->createForm(CourseType::class,$course);
        $courseForm->handleRequest($request);
        if($courseForm->isSubmitted() && $courseForm->isValid()){
            //$em->persist($course) pas obligatoire car Symfony a déjà associé notre objet.
            $course->setDateModified(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success','Le cours a été modifié');
            return $this->redirectToRoute('app_cours_show',['id'=>$course->getId()]);
        }
        return $this->render('course/edit.html.twig',
            ["courseForm"=>$courseForm]
        );
    }

    #[Route('/create', name: 'create',methods: ['GET','POST'])]
    public function create(Request $request,EntityManagerInterface $em): Response
    {
        //Crée mon Objet
        $course = new Course();
        //Crée le formulaire et associer l'objet au formulaire
        $courseForm = $this->createForm(CourseType::class, $course);
        //traiter le formulaire
        //Associe mon formulaire à mon objet
        $courseForm->handleRequest($request);
        //Je teste si le formulaire a été soumis
        if($courseForm->isSubmitted() && $courseForm->isValid()){
            //J'enregistre dans ma BD le cours.
            $em->persist($course);
            $em->flush();
            $this->addFlash('success','Le cours a été ajouté !');
            return $this->redirectToRoute('app_cours_show',['id'=>$course->getId()]);
        }
        return $this->render('course/create.html.twig',
            ['courseForm' => $courseForm]
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

    #[Route('/{id}/supprimer', name: 'supprimer',requirements:['id'=>'\d+'],methods: ['GET'])]
    function delete(Course $course,Request $request,EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('delete-'.$course->getId(),$request->get('token'))) {
            try {
                $em->remove($course);
                $em->flush();
                $this->addFlash("success", "Le cours a été supprimé");
            } catch (\Exception $ex) {
                $this->addFlash('danger', "Le cours n'a pas été supprimé");
            }
        }else{
            $this->addFlash('danger', "Le cours n'a pas été supprimé: problème de token");
        }
        return $this->redirectToRoute("app_cours_list");
    }
}

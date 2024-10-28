<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/categories', name: 'app_category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete',requirements:['id'=>'\d+'], methods: ['GET'])]
    public function delete(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        try {
          /*  if(count($category->getCourses())>0){
                //Si la catégorie possède des cours, on les supprime.
                foreach ($category->getCourses() as $course){
                    $category->removeCourse($course);
                }
            }*/
            $em->remove($category);
            $em->flush();
            $this->addFlash("success", "La catégorie a été supprimée");
        } catch (\Exception $ex) {
            $this->addFlash('danger', "Le catégorie n'a pas été supprimée ".$ex->getMessage());
        }
        return $this->redirectToRoute('app_cours_list');
    }
}

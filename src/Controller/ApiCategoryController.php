<?php
namespace App\Controller;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiCategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'api_category_list',methods: ['GET'])]
    public function list(CategoryRepository $categoryRepository, SerializerInterface $serializer): JsonResponse{
        $categories = $categoryRepository->findAll();
        //on serialise les données au format JSON
       // $result = $serializer->serialize($categories, 'json',['groups' => 'getCategoriesFull']);
        //On retourne au format JSON une réponse
        //return new JsonResponse($result,Response::HTTP_OK,[],true);
        return $this->json($categories,Response::HTTP_OK,[],['groups'=>['getCategoriesFull']]);
    }

}
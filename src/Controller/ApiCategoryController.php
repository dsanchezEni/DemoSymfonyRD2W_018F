<?php
namespace App\Controller;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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

    #[Route('/api/categories/{id}', name: 'api_category_read', requirements:['id'=>'\d+'],methods: ['GET'])]
    public function read(?Category $category, SerializerInterface $serializer): JsonResponse{
        if(!$category){
            return new JsonResponse(null,Response::HTTP_NOT_FOUND);
        }
        return $this->json($category,Response::HTTP_OK,[],['groups'=>'getCategories']);
    }

    #[Route('/api/categories', name: 'api_category_create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer,EntityManagerInterface $em): JsonResponse{
       //On récupére les données dans la requete.
        $data = $request->getContent();
        $category = $serializer->deserialize($data, Category::class, 'json');
        $em->persist($category);
        $em->flush();
        //On retourne une réponse avec le code statut 201 et la nouvelle catégorie au format JSON
        return $this->json($category,
            Response::HTTP_CREATED,
            ["Location"=>$this->generateUrl('api_category_read',["id"=>$category->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL)
            ],
            ['groups'=>'getCategories']);
    }

    #[Route('/api/categories/{id}', name: 'api_category_update',requirements:['id'=>'\d+'], methods: ['PUT'])]
    public function update(?Category $category,Request $request,  SerializerInterface $serializer,EntityManagerInterface $em): JsonResponse{
        if(!$category){
            return new JsonResponse(null,Response::HTTP_NOT_FOUND);
        }
        //On récupére les données dans la requete.
        $data = $request->getContent();
        //On injecte les données reçues dans l'objet categorie récupéré
        //OBJCT_TO_POPULATE permet de préciser qu'on récupère les données dans l'objet $category déjà existant.
        $serializer->deserialize($data, Category::class, 'json',[AbstractNormalizer::OBJECT_TO_POPULATE=>$category]);
        $em->flush();
        //On retourne une réponse avec le code statut 201 et la nouvelle catégorie au format JSON
        return $this->json(null,Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/categories/{id}', name: 'api_category_delete',requirements:['id'=>'\d+'], methods: ['DELETE'])]
    public function delete(?Category $category,EntityManagerInterface $em): JsonResponse{
        if(!$category){
            return new JsonResponse(null,Response::HTTP_NOT_FOUND);
        }
        $em->remove($category);
        $em->flush();
        //On retourne une réponse avec le code statut 201 et la nouvelle catégorie au format JSON
        return $this->json(null,Response::HTTP_NO_CONTENT);
    }
}
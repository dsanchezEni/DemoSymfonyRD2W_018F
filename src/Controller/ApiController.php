<?php
namespace App\Controller;
use App\Models\Region;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController{
    #[Route('/regions', name: 'api_regions', methods: ['GET'])]
    public function regions(SerializerInterface $serializer):Response{
        $content = file_get_contents('https://geo.api.gouv.fr/regions');
        //$regions = $serializer->decode($content, 'json');
        //$regionsObjet = $serializer->denormalize($regions, Region::class.'[]');
        $regions = $serializer->deserialize($content, Region::class.'[]', 'json');
        //dd($regionsObjet);
        //dd($regions);
        return $this->render('api/regions.html.twig', [
            'regions' => $regions,
        ]);
    }
}
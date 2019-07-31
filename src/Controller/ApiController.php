<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(SerializerInterface $serialize)
    {
        $regions = file_get_contents('https://geo.api.gouv.fr/regions');
        $lesRegions = $serialize->decode($regions, 'json');
        //dd($lesRegions);
        return $this->render('api/index.html.twig', [
            'lesRegions' => $lesRegions
        ]);
    }
}

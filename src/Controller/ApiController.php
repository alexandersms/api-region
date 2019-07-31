<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    /**
     * @Route("/", name="api")
     */
    public function index(SerializerInterface $serialize)
    {
        $regions = file_get_contents('https://geo.api.gouv.fr/regions');
        // Methode Decode
        //$lesRegions = $serialize->decode($regions, 'json');

        // Methode Denormalize
        //$objRegions = $serialize->denormalize($lesRegions, 'App\Entity\Region[]');

        // Deserialisation, qui fait du decode et du denormalize en même temps
        $deserRegions = $serialize->deserialize($regions, 'App\Entity\Region[]', 'json');

        //dd($objRegions);
        return $this->render('api/index.html.twig', [
            'lesRegions' => $deserRegions
        ]);
    }


    /**
     * @Route("/listedpt", name="api_listedpt")
     */
    public function listedpt(SerializerInterface $serialize, Request $request)
    {
        // Je recupère la région selectionnée dans le formulaire
        $codeRegion = $request->query->get('region');
        // Je recupère les région
        $regions = file_get_contents('https://geo.api.gouv.fr/regions');
        // Deserialisation des régions
        $deserRegions = $serialize->deserialize($regions, 'App\Entity\Region[]', 'json');

        // Je récupère la liste des départements
        if ($codeRegion == null || $codeRegion == "Toutes") {
            $mesDpt = file_get_contents('https://geo.api.gouv.fr/departements');
        } else {
            $mesDpt = file_get_contents('https://geo.api.gouv.fr/regions/' . $codeRegion . '/departements');
        }

        //décodage du format json en tableau
        $mesDpt = $serialize->decode($mesDpt, 'json');

        return $this->render('api/listeDpt.html.twig', [
            'lesRegions' => $deserRegions,
            'mesDpt' => $mesDpt
        ]);
    }
}

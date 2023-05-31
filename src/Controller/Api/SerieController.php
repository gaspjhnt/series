<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/series', name: 'api_serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'retrieve_all', methods: ['GET'])]
    public function retrieveAll(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();

        return $this->json($series, 200, [], ['groups' => 'serie_data']);
    }

    #[Route('/{id}', name: 'retrieve_one', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function retrieveOne(int $id): Response
    {

    }

    #[Route('/{id}', name: 'delete_one', methods: ['DELETE'])]
    public function deleteOne(int $id): Response
    {
    }

    #[Route('/{id}', name: 'update_one', methods: ['PUT'])]
    public function updateOne(int $id, \Symfony\Component\HttpFoundation\Request $request, SerieRepository $serieRepository): Response
    {
        $serie=$serieRepository->find($id);
        if($serie){
            //transforme une string au format json en objet php
            $data= json_decode($request->getContent());
            if($data->value) {
                $serie->setNbLike($serie->getNbLike() + 1);
            }else{
                $serie->setNbLike($serie->getNbLike()-1);
            }
            $serieRepository->save($serie,true);

            return $this->json(['nbLike' => $serie->getNbLike()]);
        }
        return $this->json(['error' => 'Serie not found']);
    }




    #[Route('', name: 'add_one', methods: ['POST'])]
    public function addOne(Request $request, SerializerInterface $serializer): Response
    {
        $json = $request->getContent();

        $serie = $serializer->deserialize($json, Serie::class, 'json');

        dd($serie);

    }
}
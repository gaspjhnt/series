<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {

        //TODO renvoyer la liste des series

        $series = $serieRepository->findBy([], ["popularity" => "DESC"], 50, 0);


        return $this->render('serie/list.html.twig', [
            'series' => $series
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ["id" => "\d+"])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
        //TODO renvoyer le detail d'une serie
        return $this->render('serie/show.html.twig',[
            'serie' => $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(): Response
    {
        //TODO renvoyer un form pour add une nouvelle série


        return $this->render('serie/add.html.twig',);
    }
}

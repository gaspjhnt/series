<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/season', name: 'season_')]
class SeasonController extends AbstractController
{
    #[Route('/add/{id}', name: 'add', requirements: ['id' => '\d+'])]
    public function add(
        SeasonRepository $seasonRepository,
        SerieRepository $serieRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        int $id
    ): Response
    {
        //Recupération de l'instance de la série
        $serie = $serieRepository->find($id);


        $season = new Season();
        $season->setSerie($serie);
        $seasonForm = $this->createForm(SeasonType::class, $season);

        $seasonForm->handleRequest($request);

        if ($seasonForm->isSubmitted() && $seasonForm->isValid()){

            $seasonRepository->save($season, true);

            // possible de faire comme ça avec l'entity manager
//            $entityManager->persist($season);
//            $entityManager->flush();

            $this->addFlash('success', 'Season added on ' . $season->getSerie()->getName() . ' !');
            return $this->redirectToRoute('serie_show', ['id' => $season->getSerie()->getId()]);

        }
        return $this->render('season/add.html.twig', [
            'seasonForm' => $seasonForm->createView()
        ]);
    }
}

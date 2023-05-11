<?php

namespace App\Controller;

use App\Repository\SerieRepository;
use ContainerNAoJcXn\get_Container_Private_SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        return $this->render("main/home.html.twig");
    }


    #[Route('/test', name: 'main_test')]
    public function test(EntityManagerInterface $entityManager, SerieRepository $serieRepository): Response
    {

        $serie = new Serie();
        $serie
            ->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime())
            ->setGenres("Thriller/Drama")
            ->setName("Utopia")
            ->setFirstAirDate(new \DateTime("-2 year"))
            ->setLastAirDate(new \DateTime("-2 month"))
            ->setPopularity(500)
            ->setPoster("poster.png")
            ->setStatus("Canceled")
            ->setTmdbId(123456)
            ->setVote(5);


        /*        dump($serie);

                //Sauvegarde de mon instance grace a l'entitymanager
                $entityManager->persist($serie);
                $entityManager->flush();

                dump($serie);

                //Si j'ai un id j'update
                $serie->setName("Code Quantum");
                $entityManager->persist($serie);
                $entityManager->flush();

                dump($serie);

                //je supprime
                $entityManager->remove($serie);
                $entityManager->flush();*/


        $serieRepository->save($serie, true);

        dump($serie);



        $username = "leGaspOfficiel";
        $serie = ["title" => "Suits", "year" => 2011];

        return $this->render("main/test.html.twig", [
            "nameOfUser" => $username,
            "mySerie" => $serie
        ]);
    }
}

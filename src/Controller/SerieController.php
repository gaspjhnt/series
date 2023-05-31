<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ["page" => "\d+"])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {

        //$series = $serieRepository->findBy([], ["popularity" => "DESC"], 50, 0);
        //$series = $serieRepository->findBestSeries();

        $nbSeries = $serieRepository->count([]);
        $maxPage = ceil($nbSeries / Serie::MAX_RESULT);


        //Gestion page inférieur a 1
        if ($page < 1){
            return $this->redirectToRoute('serie_list');
        }

        //Gestion page supérieur a maxpage
        if ($page > $maxPage){
            return $this->redirectToRoute('serie_list', ['page' => $maxPage]);
        }

        $series = $serieRepository->findSeriesWithPagination($page);

        return $this->render('serie/list.html.twig', [
            'page' => $page,
            'maxPage' => $maxPage,
            'series' => $series
        ]);
    }

    #[Route('/detail/{id}', name: 'show', requirements: ["id" => "\d+"])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        //TODO renvoyer le detail d'une serie

        $serie = $serieRepository->find($id);

        if (!$serie){
            //Permet de lancer une erreur 404
            throw $this->createNotFoundException("Oops ! Serie not found !");
        }

        return $this->render('serie/show.html.twig',[
            'serie' => $serie
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/add', name: 'add')]
    public function add(Request $request, SerieRepository $serieRepository): Response
    {
        //TODO renvoyer un form pour add une nouvelle série
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        //Permet d'extraire les données de la requête
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()){

            //traitement de la donnée
            $genres = $serieForm->get('genres')->getData();
            $serie->setGenres(implode(' / ', $genres));
            $serie->setDateCreated(new \DateTime());

            $serieRepository->save($serie, true);

            $this->addFlash('success', 'Félicitations tu es vraiment excellent dans ce que tu enretprends je t\'invite très grandement a poursuivre tes efforts. Je sais que quelques detraqueur peuvent s\'averer méchants avec toi, MAIS SURTOUT, ne les écoutes pas !!!! Un grand sage a dit un jour, si vous voyez des terroristes armés jusqu\'au dents, surtout ALLEZ VOUS EN !');

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/add.html.twig', [
            'serieForm2' => $serieForm->createView()
        ]);
    }


    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function edit(int $id, SerieRepository $serieRepository){

        $serie = $serieRepository->find($id);
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->get('genres')->setData(explode(' / ', $serie->getGenres()));

        return $this->render('serie/update.html.twig',[
            'serieForm' => $serieForm->createView()
        ]);

    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, SerieRepository $serieRepository){

        $serie = $serieRepository->find($id);

        $serieRepository->remove($serie, true);

        $this->addFlash('success', $serie->getName() . ' has been removed !');

        return $this->redirectToRoute('serie_list');

    }



}

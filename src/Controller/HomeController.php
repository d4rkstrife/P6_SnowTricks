<?php

namespace App\Controller;

use App\Service\Paginator;
use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(FigureRepository $figureRepository, Paginator $paginator): Response
    {
        $datas = $figureRepository->findBy([], ['createdAt' => 'desc'],  $paginator->numberOfItems(), 0);

        return $this->render('home/index.html.twig', [
            'datas' => $datas,
            'page' => $paginator->getPage(),
            'maxPage' => $paginator->numberOfPages(count($figureRepository->findAll()))
        ]);
    }
}

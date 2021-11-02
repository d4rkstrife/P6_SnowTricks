<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{

    #[Route('/page={page}', name: 'home', requirements: ['page' => '\d+'])]
    public function index(FigureRepository $figureRepository, int $page = 1): Response
    {
        $datas = $figureRepository->findBy([], ['createdAt' => 'desc'], $page * 2, 0);

        return $this->render('home/index.html.twig', [
            'datas' => $datas,
            'currentPage' => $page
        ]);
    }
}

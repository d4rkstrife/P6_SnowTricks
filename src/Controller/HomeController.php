<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(FigureRepository $figureRepository): Response
    {
        $datas = $figureRepository->findBy([], ['createdAt' => 'desc'], 4, 0);

        return $this->render('home/index.html.twig', [
            'datas' => $datas
        ]);
    }
}

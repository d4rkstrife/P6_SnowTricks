<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private FigureRepository $figureRepository;

    #[Route('/', name: 'home')]
    public function index(FigureRepository $figureRepository): Response
    {
        $datas = $figureRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'datas' => $datas
        ]);
    }
}

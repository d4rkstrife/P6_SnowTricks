<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    #[Route('/figure/{slug}', name: 'figure')]
    public function figureShow(FigureRepository $figureRepository, string $slug): Response
    {
        $datas = $figureRepository->findOneBy(['slug' => $slug]);
        return $this->render('home/figure.html.twig', [
            'datas' => $datas
        ]);
    }
}

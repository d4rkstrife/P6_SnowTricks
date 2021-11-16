<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
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

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('figure/figure.html.twig', [
            'datas' => $datas,
            'form' => $form->createView()
        ]);
    }

    #[Route('/modification/{slug}', name: 'modification')]
    public function figureModification(FigureRepository $figureRepository, string $slug): Response
    {
        $datas = $figureRepository->findOneBy(['slug' => $slug]);

        return $this->render('figure/modification.html.twig', [
            'datas' => $datas
        ]);
    }
}

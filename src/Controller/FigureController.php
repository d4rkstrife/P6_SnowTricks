<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\FigureRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    #[Route('/figure/{slug}', name: 'figure')]
    public function figureShow(FigureRepository $figureRepository, CommentRepository $commentRepository, string $slug, Request $request, EntityManagerInterface $em, Paginator $paginator): Response
    {
        $figure = $figureRepository->findOneBy(['slug' => $slug]);
        $comments = $commentRepository->findBy(['relatedFigure' => $figure], ['date' => 'desc'], $paginator->numberOfComments(), 0);


        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setDate(date_create());
            $comment->setRelatedFigure($figure);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
        }

        return $this->render('figure/figure.html.twig', [
            'figure' => $figure,
            'comments' => $comments,
            'page' => $paginator->getPage(),
            'maxPage' => $paginator->numberOfPages(count($commentRepository->findBy(['relatedFigure' => $figure])), 'comments'),
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

<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Service\Paginator;
use App\Repository\FigureRepository;
use App\Repository\CommentRepository;
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
        $comments = $commentRepository->findBy(['relatedFigure' => $figure], ['date' => 'desc'], $paginator->numberOfItems('app.commentperpage'), 0);


        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump('isValid');
            $comment->setDate(date_create());
            $comment->setRelatedFigure($figure);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('figure', ['slug' => $slug]);
        }

        return $this->render('figure/figure.html.twig', [
            'figure' => $figure,
            'comments' => $comments,
            'page' => $paginator->getPage(),
            'maxPage' => $paginator->numberOfPages(count($commentRepository->findBy(['relatedFigure' => $figure]))),
            'form' => $form->createView()
        ]);
    }

    #[Route('/modification/{slug}', name: 'modification')]
    public function figureModification(FigureRepository $figureRepository, Request $request, EntityManagerInterface $em, string $slug): Response
    {
        $figure = $figureRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setModifiedAt(date_create());

            $em->flush();
        }

        return $this->render('figure/modification.html.twig', [
            'datas' => $figure,
            'form' => $form->createView()
        ]);
    }

    #[Route('/newFigure', name: 'newFigure')]
    public function newFigure(Request $request, EntityManagerInterface $em)
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setCreatedAt(date_create());
            $figure->setModifiedAt(date_create());
            $figure->setSlug($figure->getName());
            $figure->setAutor($this->getUser());
            $em->persist($figure);
            $em->flush();
            return $this->redirectToRoute('figure', ['slug' => $figure->getSlug()]);
        }
        return $this->render('figure/newFigure.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

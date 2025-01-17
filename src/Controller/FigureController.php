<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Comment;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Service\Paginator;
use App\Entity\FigureVideo;
use App\Entity\FigurePicture;
use App\Service\PictureService;
use App\Repository\FigureRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FigurePictureRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FigureController extends AbstractController
{
    #[Route('/figure/{slug}', name: 'figure')]
    public function figureShow(
        FigureRepository $figureRepository,
        CommentRepository $commentRepository,
        string $slug,
        Request $request,
        EntityManagerInterface $em,
        Paginator $paginator
    ): Response {
        $figure = $figureRepository->findOneBy(['slug' => $slug]);
        $comments = $commentRepository->findBy(['relatedFigure' => $figure], ['date' => 'desc'], $paginator->numberOfItems('app.commentperpage'), 0);


        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
    #[IsGranted('ROLE_USER')]
    public function figureModification(
        ValidatorInterface $validator,
        FigureRepository $figureRepository,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        string $slug,
        PictureService $pictureService
    ): Response {

        $figure = $figureRepository->findOneBy(['slug' => $slug]);
        $figurePictures = $figure->getFigurePictures();
        $main = true;
        foreach ($figurePictures as $figurePicture) {
            if ($figurePicture->getMain() === true) {
                $main = false;
            }
        }

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFiles = $form->get('picture')->getData();
            foreach ($pictureFiles as $pictureFile) {
                $violations = $validator->validate($pictureFile, new Image(['maxWidth' => 3840, 'maxHeight' => 2860, 'maxSize' => "3M", "mimeTypes" => ["image/jpeg", "image/png"]]));
                if (count($violations) > 0) {
                    foreach ($violations as $violation) {
                        $this->addFlash('error', $pictureFile->getClientOriginalName() . " " . $violation->getMessage());
                        return $this->render('figure/modification.html.twig', [
                            'figure' => $figure,
                            'form' => $form->createView()
                        ]);
                        //                        dd($violation->getMessage(), $pictureFile->getClientOriginalName());
                    }
                }
                $figurePicture = $pictureService->uploadPicture($pictureFile, $main);

                $figure->addFigurePicture($figurePicture);
                if ($main === true) {
                    $main = false;
                }
            }

            $figure->setSlug($slugger->slug($figure->getName()));
            $figure->setModifiedAt(date_create());
            $em->flush();

            $this->addFlash('success', 'Modifié avec succès');
            return $this->redirectToRoute('figure', ['slug' => $figure->getSlug()]);
        }

        return $this->render('figure/modification.html.twig', [
            'figure' => $figure,
            'form' => $form->createView()
        ]);
    }


    #[Route('/newFigure', name: 'newFigure')]
    #[IsGranted('ROLE_USER')]
    public function newFigure(
        ValidatorInterface $validator,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        PictureService $pictureService
    ) {
        $figure = new Figure();


        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFiles = $form->get('picture')->getData();

            $main = true;
            foreach ($pictureFiles as $pictureFile) {
                $violations = $validator->validate(
                    $pictureFile,
                    new Image(['maxWidth' => 2160, 'maxHeight' => 3840, 'maxSize' => "3M", "mimeTypes" => ["image/jpeg", "image/png"]])
                );
                if (count($violations) > 0) {
                    foreach ($violations as $violation) {
                        $this->addFlash('error', $pictureFile->getClientOriginalName() . " " . $violation->getMessage());
                        return $this->render('figure/newFigure.html.twig', [
                            'form' => $form->createView()
                        ]);
                        //                        dd($violation->getMessage(), $pictureFile->getClientOriginalName());
                    }
                }
                $figurePicture = $pictureService->uploadPicture($pictureFile, $main);
                $figure->addFigurePicture($figurePicture);
                if ($main === true) {
                    $main = false;
                }
            }
            $figure->setCreatedAt(date_create());
            $figure->setModifiedAt(date_create());
            $figure->setSlug($slugger->slug($figure->getName()));
            $figure->setAutor($this->getUser());

            $em->persist($figure);

            $em->flush();
            $this->addFlash('success', 'Ajouté avec succès');
            return $this->redirectToRoute('home');
        }
        return $this->render('figure/newFigure.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{pictureId}/{figureId}', name: 'deletePicture')]
    #[IsGranted('ROLE_USER')]
    public function deletePicture(Figure $figureId, FileSystem $fileSystem, FigurePicture $pictureId, FigurePictureRepository $figurePictureRepo, FigureRepository $figureRepo, EntityManagerInterface $em)
    {

        $picture = $figurePictureRepo->findOneBy(['id' => $pictureId]);
        $figure = $figureRepo->findOneBy(['id' => $figureId]);
        $figure->removeFigurePicture($picture);
        $fileSystem->remove(
            $this->getParameter('figurePictureDirectory') . "/" . $picture->getFilename()
        );

        $em->flush();

        return $this->redirectToRoute('modification', ['slug' => $figure->getSlug()]);
    }

    #[Route('/mainPicture/{picture}', name: 'makePictureMain')]
    #[IsGranted('ROLE_USER')]
    public function makePictureMain(FigurePicture $picture, EntityManagerInterface $em)
    {

        $figure = $picture->getRelatedFigure();
        foreach ($figure->getFigurePictures() as $image) {
            $image->setMain($picture->getId() === $image->getId());
        }
        $em->flush();


        return $this->redirectToRoute('modification', ['slug' => $figure->getSlug()]);
    }

    #[Route('deleteFigure/{figure}', name: 'deleteFigure')]
    #[IsGranted('ROLE_USER')]
    public function deleteFigure(Figure $figure, EntityManagerInterface $em, Filesystem $fileSystem)
    {
        $comments = $figure->getComments();
        $pictures = $figure->getFigurePictures();
        $videos = $figure->getRelatedVideos();
        foreach ($comments as $comment) {
            $figure->removeComment($comment);
        }
        foreach ($pictures as $picture) {
            $figure->removeFigurePicture($picture);
            $fileSystem->remove(
                $this->getParameter('figurePictureDirectory') . "/" . $picture->getFilename()
            );
        }
        foreach ($videos as $video) {
            $figure->removeRelatedVideo($video);
        }
        $em->remove($figure);
        $em->flush();

        return $this->redirectToRoute('home');
    }
}

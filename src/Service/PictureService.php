<?php

namespace App\Service;

use App\Entity\FigurePicture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureService
{
    private SluggerInterface $slugger;

    /**
     * @var string
     */
    private $figurePictureDirectory;
    public function __construct(SluggerInterface $slugger, string $figurePictureDirectory)
    {
        $this->figurePictureDirectory = $figurePictureDirectory;
        $this->slugger = $slugger;
    }


    public function uploadPicture($pictureFile, $main): FigurePicture
    {
        $name = $this->renamePicture($pictureFile);
        // Move the file to the directory where brochures are stored
        try {
            $pictureFile->move(
                $this->figurePictureDirectory,
                $name
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file uploads
        }
        return $this->createPicture($main, $name);
    }


    public function renamePicture($pictureFile): string
    {
        $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        return $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();
    }
    public function createPicture(bool $main, string $name): FigurePicture
    {
        $figurePicture = new FigurePicture();
        $figurePicture->setFilename($name);
        $figurePicture->setMain($main);
        return $figurePicture;
    }
}

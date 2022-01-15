<?php

namespace App\Service;

use App\Entity\FigurePicture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureService
{
    private SluggerInterface $slugger;


    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function uploadPicture($pictureFile, $fileDirectory, $newFilename)
    {
        // Move the file to the directory where brochures are stored
        try {
            $pictureFile->move(
                $fileDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file uploads
        }
    }
    public function renamePicture($pictureFile)
    {
        $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        return $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();
    }
}

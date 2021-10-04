<?php

namespace App\Entity;

use App\Repository\FigurePictureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FigurePictureRepository::class)
 */
class FigurePicture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity=Figure::class, inversedBy="figurePictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relatedFigure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getRelatedFigure(): ?Figure
    {
        return $this->relatedFigure;
    }

    public function setRelatedFigure(?Figure $relatedFigure): self
    {
        $this->relatedFigure = $relatedFigure;

        return $this;
    }
}

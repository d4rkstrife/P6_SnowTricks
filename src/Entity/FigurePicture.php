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
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=Figure::class, inversedBy="figurePictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relatedFigure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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

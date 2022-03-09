<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FigureVideoRepository;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FigureVideoRepository::class)
 */
class FigureVideo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    ///^(?:<iframe[^>]*)(?:(?:\/>)|(?:>.*?<\/iframe>))$/
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     * pattern="/^<iframe(?:\b|_).*?(?:\b|_)(?:youtube)(?:\b|_).*?(?:\b|_)iframe>$/",
     * message="Seuls les liens iframe youtube sont acceptés"
     * )
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=Figure::class, inversedBy="relatedVideos")
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

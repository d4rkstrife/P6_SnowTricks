<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FigureGroupRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=FigureGroupRepository::class)
 */
class FigureGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Le nom doit faire au moins {{ limit }} caractères"
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Figure::class, mappedBy="figureGroup")
     */
    private $relatedFigures;

    public function __construct()
    {
        $this->relatedFigures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Figure[]
     */
    public function getRelatedFigures(): Collection
    {
        return $this->relatedFigures;
    }

    public function addRelatedFigure(Figure $relatedFigure): self
    {
        if (!$this->relatedFigures->contains($relatedFigure)) {
            $this->relatedFigures[] = $relatedFigure;
            $relatedFigure->setFigureGroup($this);
        }

        return $this;
    }

    public function removeRelatedFigure(Figure $relatedFigure): self
    {
        if ($this->relatedFigures->removeElement($relatedFigure)) {
            // set the owning side to null (unless already changed)
            if ($relatedFigure->getFigureGroup() === $this) {
                $relatedFigure->setFigureGroup(null);
            }
        }

        return $this;
    }
}

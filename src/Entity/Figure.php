<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\FigureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=FigureRepository::class)
 * @UniqueEntity(fields={"name"}, message="Nom déjà utilisé")
 */
class Figure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $name;


    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $autor;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="relatedFigure", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=FigurePicture::class, mappedBy="relatedFigure", orphanRemoval=true, cascade={"persist"})
     */
    private $figurePictures;

    /**
     * @ORM\OneToMany(targetEntity=FigureVideo::class, mappedBy="relatedFigure", orphanRemoval=true, cascade={"persist"})
     */
    private $relatedVideos;

    /**
     * @ORM\ManyToOne(targetEntity=FigureGroup::class, inversedBy="relatedFigures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figureGroup;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->figurePictures = new ArrayCollection();
        $this->relatedVideos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAutor(): ?User
    {
        return $this->autor;
    }

    public function setAutor(?User $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRelatedFigure($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRelatedFigure() === $this) {
                $comment->setRelatedFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FigurePicture[]
     */
    public function getFigurePictures(): Collection
    {
        return $this->figurePictures;
    }

    public function addFigurePicture(FigurePicture $figurePicture): self
    {
        if (!$this->figurePictures->contains($figurePicture)) {
            $this->figurePictures[] = $figurePicture;
            $figurePicture->setRelatedFigure($this);
        }

        return $this;
    }

    public function removeFigurePicture(FigurePicture $figurePicture): self
    {
        if ($this->figurePictures->removeElement($figurePicture)) {
            // set the owning side to null (unless already changed)
            if ($figurePicture->getRelatedFigure() === $this) {
                $figurePicture->setRelatedFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FigureVideo[]
     */
    public function getRelatedVideos(): Collection
    {
        return $this->relatedVideos;
    }

    public function addRelatedVideo(FigureVideo $relatedVideo): self
    {
        if (!$this->relatedVideos->contains($relatedVideo)) {
            $this->relatedVideos[] = $relatedVideo;
            $relatedVideo->setRelatedFigure($this);
        }

        return $this;
    }

    public function removeRelatedVideo(FigureVideo $relatedVideo): self
    {
        if ($this->relatedVideos->removeElement($relatedVideo)) {
            // set the owning side to null (unless already changed)
            if ($relatedVideo->getRelatedFigure() === $this) {
                $relatedVideo->setRelatedFigure(null);
            }
        }

        return $this;
    }

    public function getFigureGroup(): ?FigureGroup
    {
        return $this->figureGroup;
    }

    public function setFigureGroup(?FigureGroup $figureGroup): self
    {
        $this->figureGroup = $figureGroup;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTime
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTime $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}

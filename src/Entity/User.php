<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mailIsValidate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registrationKey;

    /**
     * @ORM\OneToMany(targetEntity=Figure::class, mappedBy="autor", orphanRemoval=true)
     */
    private $figures;

    /**
     * @ORM\OneToOne(targetEntity=ProfilPicture::class, mappedBy="relatedUser", cascade={"persist", "remove"})
     */
    private $profilPicture;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->figures = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getMailIsValidate(): ?bool
    {
        return $this->mailIsValidate;
    }

    public function setMailIsValidate(bool $mailIsValidate): self
    {
        $this->mailIsValidate = $mailIsValidate;

        return $this;
    }

    public function getRegistrationKey(): ?string
    {
        return $this->registrationKey;
    }

    public function setRegistrationKey(string $registrationKey): self
    {
        $this->registrationKey = $registrationKey;

        return $this;
    }

    /**
     * @return Collection|Figure[]
     */
    public function getFigures(): Collection
    {
        return $this->figures;
    }

    public function addFigure(Figure $figure): self
    {
        if (!$this->figures->contains($figure)) {
            $this->figures[] = $figure;
            $figure->setAutor($this);
        }

        return $this;
    }

    public function removeFigure(Figure $figure): self
    {
        if ($this->figures->removeElement($figure)) {
            // set the owning side to null (unless already changed)
            if ($figure->getAutor() === $this) {
                $figure->setAutor(null);
            }
        }

        return $this;
    }

    public function getProfilPicture(): ?ProfilPicture
    {
        return $this->profilPicture;
    }

    public function setProfilPicture(ProfilPicture $profilPicture): self
    {
        // set the owning side of the relation if necessary
        if ($profilPicture->getRelatedUser() !== $this) {
            $profilPicture->setRelatedUser($this);
        }

        $this->profilPicture = $profilPicture;

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}

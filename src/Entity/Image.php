<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Table('image')]
#[ORM\Entity]
class Image
{
    use TimestampableEntity;

    public const CATEGORY_PROFILE_IMAGE = 'profile_image';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $originalFileName;

    #[ORM\Column(length: 255)]
    private ?string $cryptedFileName;

    #[ORM\Column(length: 255)]
    private ?string $category;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(string $originalFileName): self
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    public function getCryptedFileName(): ?string
    {
        return $this->cryptedFileName;
    }

    public function setCryptedFileName(string $cryptedFileName): self
    {
        $this->cryptedFileName = $cryptedFileName;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

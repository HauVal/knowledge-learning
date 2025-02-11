<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // The user who made the purchase
    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // The purchased cursus (if applicable)
    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?Cursus $cursus = null;

    // The purchased lesson (if applicable)
    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?Lesson $lesson = null;

    // The date when the purchase was made
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $purchase_date = null;

    // Get the purchase ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Get the user who made the purchase
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Set the user who made the purchase
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // Get the purchased cursus (if any)
    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    // Set the purchased cursus
    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }

    // Get the purchased lesson (if any)
    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    // Set the purchased lesson
    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    // Get the purchase date
    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchase_date;
    }

    // Set the purchase date
    public function setPurchaseDate(\DateTimeInterface $purchase_date): static
    {
        $this->purchase_date = $purchase_date;

        return $this;
    }
}
<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // The user who obtained this certification
    #[ORM\ManyToOne(inversedBy: 'certifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    // The cursus associated with this certification
    #[ORM\ManyToOne(inversedBy: 'certifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cursus $cursus;

    // The date when the certification was obtained
    #[ORM\Column]
    private ?\DateTimeImmutable $obtained_at = null;

    // Get the certification ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Get the user who obtained the certification
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Set the user who obtained the certification
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // Get the associated cursus
    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    // Set the associated cursus
    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }

    // Get the date when the certification was obtained
    public function getObtainedAt(): ?\DateTimeImmutable
    {
        return $this->obtained_at;
    }

    // Set the date when the certification was obtained
    public function setObtainedAt(\DateTimeImmutable $obtained_at): static
    {
        $this->obtained_at = $obtained_at;

        return $this;
    }
}
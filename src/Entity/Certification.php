<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a certification obtained by a user for completing a cursus.
 */
#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    /**
     * Unique identifier of the certification.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The user who obtained this certification.
     *
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'certifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    /**
     * The cursus associated with this certification.
     *
     * @var Cursus|null
     */
    #[ORM\ManyToOne(inversedBy: 'certifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cursus $cursus;

    /**
     * The date when the certification was obtained.
     *
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $obtained_at = null;

    /**
     * Get the certification ID.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the user who obtained the certification.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the user who obtained the certification.
     *
     * @param User|null $user
     * @return static
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the associated cursus.
     *
     * @return Cursus|null
     */
    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    /**
     * Set the associated cursus.
     *
     * @param Cursus|null $cursus
     * @return static
     */
    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }

    /**
     * Get the date when the certification was obtained.
     *
     * @return \DateTimeImmutable|null
     */
    public function getObtainedAt(): ?\DateTimeImmutable
    {
        return $this->obtained_at;
    }

    /**
     * Set the date when the certification was obtained.
     *
     * @param \DateTimeImmutable $obtained_at
     * @return static
     */
    public function setObtainedAt(\DateTimeImmutable $obtained_at): static
    {
        $this->obtained_at = $obtained_at;

        return $this;
    }
}

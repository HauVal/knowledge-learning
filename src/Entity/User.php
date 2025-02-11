<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // User's full name
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    // User's email address
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    // Hashed password for authentication
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    // User roles (default is ROLE_USER)
    #[ORM\Column]
    private array $roles = ["ROLE_USER"];

    // Account activation status (false by default)
    #[ORM\Column]
    private ?bool $isActive = false;

    // List of purchases made by the user
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'user')]
    private Collection $purchases;

    // Certifications obtained by the user
    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'user')]
    private Collection $certifications;

    // Lessons validated by the user
    #[ORM\ManyToMany(targetEntity: Lesson::class)]
    #[ORM\JoinTable(name: "user_validated_lessons")]
    private Collection $validatedLessons;

    // Cursuses validated by the user
    #[ORM\ManyToMany(targetEntity: Cursus::class)]
    #[ORM\JoinTable(name: "user_validated_cursuses")]
    private Collection $validatedCursuses;

    // Cursuses purchased by the user
    #[ORM\ManyToMany(targetEntity: Cursus::class)]
    #[ORM\JoinTable(name: "user_cursus_purchase")]
    private Collection $purchasedCursuses;

    // Lessons purchased by the user
    #[ORM\ManyToMany(targetEntity: Lesson::class)]
    #[ORM\JoinTable(name: "user_lesson_purchase")]
    private Collection $purchasedLessons;

    public function __construct()
    {
        $this->purchases = new ArrayCollection();
        $this->certifications = new ArrayCollection();
        $this->purchasedCursuses = new ArrayCollection();
        $this->purchasedLessons = new ArrayCollection();
        $this->validatedLessons = new ArrayCollection();
        $this->validatedCursuses = new ArrayCollection();
    }

    // Get the user ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Get the user's name
    public function getName(): ?string
    {
        return $this->name;
    }

    // Set the user's name
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    // Get the user's email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Set the user's email
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    // Get the user's password
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Set the user's password
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    // Get the user's roles
    public function getRoles(): array
    {
        return $this->roles;
    }

    // Set the user's roles
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    // Check if the account is active
    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    // Set the account activation status
    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    // Get the unique identifier for authentication
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    // Remove sensitive data from the user object
    public function eraseCredentials(): void
    {
        // If sensitive data is stored, clear it here.
    }

    // Get all purchases made by the user
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    // Add a purchase to the user's record
    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setUser($this);
        }

        return $this;
    }

    // Remove a purchase from the user's record
    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            if ($purchase->getUser() === $this) {
                $purchase->setUser(null);
            }
        }

        return $this;
    }

    // Get the user's certifications
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    // Add a certification to the user's record
    public function addCertification(Certification $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setUser($this);
        }

        return $this;
    }

    // Remove a certification from the user's record
    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            if ($certification->getUser() === $this) {
                $certification->setUser(null);
            }
        }

        return $this;
    }

    // Get the user's purchased cursuses
    public function getPurchasedCursuses(): Collection
    {
        return $this->purchasedCursuses;
    }

    // Add a purchased cursus to the user's record
    public function addPurchasedCursus(Cursus $cursus): static
    {
        if (!$this->purchasedCursuses->contains($cursus)) {
            $this->purchasedCursuses->add($cursus);
        }

        return $this;
    }

    // Remove a purchased cursus from the user's record
    public function removePurchasedCursus(Cursus $cursus): static
    {
        $this->purchasedCursuses->removeElement($cursus);
        return $this;
    }

    // Get the user's purchased lessons
    public function getPurchasedLessons(): Collection
    {
        return $this->purchasedLessons;
    }

    // Add a purchased lesson to the user's record
    public function addPurchasedLesson(Lesson $lesson): static
    {
        if (!$this->purchasedLessons->contains($lesson)) {
            $this->purchasedLessons->add($lesson);
        }

        return $this;
    }

    // Remove a purchased lesson from the user's record
    public function removePurchasedLesson(Lesson $lesson): static
    {
        $this->purchasedLessons->removeElement($lesson);
        return $this;
    }

    // Get the user's validated lessons
    public function getValidatedLessons(): Collection
    {
        return $this->validatedLessons;
    }

    // Add a validated lesson to the user's record
    public function addValidatedLesson(Lesson $lesson): static
    {
        if (!$this->validatedLessons->contains($lesson)) {
            $this->validatedLessons->add($lesson);
        }

        return $this;
    }

    // Remove a validated lesson from the user's record
    public function removeValidatedLesson(Lesson $lesson): static
    {
        $this->validatedLessons->removeElement($lesson);
        return $this;
    }

    // Get the user's validated cursuses
    public function getValidatedCursuses(): Collection
    {
        return $this->validatedCursuses;
    }

    // Add a validated cursus to the user's record
    public function addValidatedCursus(Cursus $cursus): static
    {
        if (!$this->validatedCursuses->contains($cursus)) {
            $this->validatedCursuses->add($cursus);
        }

        return $this;
    }
}


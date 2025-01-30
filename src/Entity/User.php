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

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = ["ROLE_USER"];

    #[ORM\Column]
    private ?bool $isActive = false;

    /**
     * @var Collection<int, Purchase>
     */
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'user')]
    private Collection $purchases;

    /**
     * @var Collection<int, Certification>
     */
    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'user')]
    private Collection $certifications;

    /**
     * @var Collection<int, Lesson>
     */
    #[ORM\ManyToMany(targetEntity: Lesson::class)]
    #[ORM\JoinTable(name: "user_validated_lessons")]
    private Collection $validatedLessons;

    /**
     * @var Collection<int, Cursus>
     */
    #[ORM\ManyToMany(targetEntity: Cursus::class)]
    #[ORM\JoinTable(name: "user_validated_cursuses")]
    private Collection $validatedCursuses;

    /**
     * @var Collection<int, Cursus>
     */
    #[ORM\ManyToMany(targetEntity: Cursus::class)]
    #[ORM\JoinTable(name: "user_cursus_purchase")]
    private Collection $purchasedCursuses;

    /**
     * @var Collection<int, Lesson>
     */
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Si tu stockes des données sensibles, efface-les ici.
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setUser($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getUser() === $this) {
                $purchase->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Certification>
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certification $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setUser($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getUser() === $this) {
                $certification->setUser(null);
            }
        }

        return $this;
    }

    public function getPurchasedCursuses(): Collection
    {
        return $this->purchasedCursuses;
    }
    
    public function addPurchasedCursus(Cursus $cursus): static
    {
        if (!$this->purchasedCursuses->contains($cursus)) {
            $this->purchasedCursuses->add($cursus);
        }
    
        return $this;
    }
    
    public function removePurchasedCursus(Cursus $cursus): static
    {
        $this->purchasedCursuses->removeElement($cursus);
        return $this;
    }
    
    public function getPurchasedLessons(): Collection
    {
        return $this->purchasedLessons;
    }
    
    public function addPurchasedLesson(Lesson $lesson): static
    {
        if (!$this->purchasedLessons->contains($lesson)) {
            $this->purchasedLessons->add($lesson);
        }
    
        return $this;
    }
    
    public function removePurchasedLesson(Lesson $lesson): static
    {
        $this->purchasedLessons->removeElement($lesson);
        return $this;
    }

    public function getValidatedLessons(): Collection
    {
        return $this->validatedLessons;
    }
    
    public function addValidatedLesson(Lesson $lesson): static
    {
        if (!$this->validatedLessons->contains($lesson)) {
            $this->validatedLessons->add($lesson);
        }
    
        return $this;
    }
    
    public function removeValidatedLesson(Lesson $lesson): static
    {
        $this->validatedLessons->removeElement($lesson);
        return $this;
    }
    
    public function getValidatedCursuses(): Collection
    {
        return $this->validatedCursuses;
    }
    
    public function addValidatedCursus(Cursus $cursus): static
    {
        if (!$this->validatedCursuses->contains($cursus)) {
            $this->validatedCursuses->add($cursus);
        }
    
        return $this;
    }
}

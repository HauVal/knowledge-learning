<?php

namespace App\Entity;

use App\Repository\CursusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CursusRepository::class)]
class Cursus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Name of the cursus
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // Price of the cursus
    #[ORM\Column]
    private ?float $price = null;

    // The theme to which this cursus belongs
    #[ORM\ManyToOne(targetEntity: Theme::class, inversedBy: "cursus")]
    #[ORM\JoinColumn(nullable: false)]
    private $theme;

    /**
     * @var Collection<int, Lesson>
     * Lessons associated with this cursus
     */
    #[ORM\OneToMany(targetEntity: Lesson::class, mappedBy: 'cursus', cascade: ['remove'])]
    private Collection $lessons;

    /**
     * @var Collection<int, Purchase>
     * Purchases related to this cursus
     */
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'cursus')]
    private Collection $purchases;

    /**
     * @var Collection<int, Certification>
     * Certifications obtained through this cursus
     */
    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'cursus')]
    private Collection $certifications;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
        $this->purchases = new ArrayCollection();
        $this->certifications = new ArrayCollection();
    }

    // Get the ID of the cursus
    public function getId(): ?int
    {
        return $this->id;
    }

    // Get the name of the cursus
    public function getName(): ?string
    {
        return $this->name;
    }

    // Set the name of the cursus
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // Get the price of the cursus
    public function getPrice(): ?float
    {
        return $this->price;
    }

    // Set the price of the cursus
    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    // Get the associated theme
    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    // Set the associated theme
    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get all lessons of the cursus
     * @return Collection<int, Lesson>
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    // Add a lesson to the cursus
    public function addLesson(Lesson $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
            $lesson->setCursus($this);
        }

        return $this;
    }

    // Remove a lesson from the cursus
    public function removeLesson(Lesson $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            // Set the owning side to null (unless already changed)
            if ($lesson->getCursus() === $this) {
                $lesson->setCursus(null);
            }
        }

        return $this;
    }

    /**
     * Get all purchases of this cursus
     * @return Collection<int, Purchase>
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    // Add a purchase to the cursus
    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setCursus($this);
        }

        return $this;
    }

    // Remove a purchase from the cursus
    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            // Set the owning side to null (unless already changed)
            if ($purchase->getCursus() === $this) {
                $purchase->setCursus(null);
            }
        }

        return $this;
    }

    /**
     * Get all certifications related to this cursus
     * @return Collection<int, Certification>
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    // Add a certification to the cursus
    public function addCertification(Certification $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setCursus($this);
        }

        return $this;
    }

    // Remove a certification from the cursus
    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            // Set the owning side to null (unless already changed)
            if ($certification->getCursus() === $this) {
                $certification->setCursus(null);
            }
        }

        return $this;
    }
}
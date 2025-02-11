<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Name of the theme
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Cursus> A collection of related Cursus entities
     */
    #[ORM\OneToMany(targetEntity: Cursus::class, mappedBy: 'theme', cascade: ['remove'])]
    private Collection $cursuses;

    public function __construct()
    {
        // Initialize the collection of Cursus entities
        $this->cursuses = new ArrayCollection();
    }

    // Get the ID of the theme
    public function getId(): ?int
    {
        return $this->id;
    }

    // Get the name of the theme
    public function getName(): ?string
    {
        return $this->name;
    }

    // Set the name of the theme
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the collection of Cursus entities related to this theme
     * 
     * @return Collection<int, Cursus>
     */
    public function getCursuses(): Collection
    {
        return $this->cursuses;
    }

    // Add a Cursus to this theme
    public function addCursus(Cursus $cursus): static
    {
        if (!$this->cursuses->contains($cursus)) {
            $this->cursuses->add($cursus);
            $cursus->setTheme($this);
        }

        return $this;
    }

    // Remove a Cursus from this theme
    public function removeCursus(Cursus $cursus): static
    {
        if ($this->cursuses->removeElement($cursus)) {
            // Set the owning side to null if not already changed
            if ($cursus->getTheme() === $this) {
                $cursus->setTheme(null);
            }
        }

        return $this;
    }
}
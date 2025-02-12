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

    /**
     * The name of the theme.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * A collection of related Cursus entities.
     *
     * @var Collection<int, Cursus>
     */
    #[ORM\OneToMany(targetEntity: Cursus::class, mappedBy: 'theme', cascade: ['remove'])]
    private Collection $cursuses;

    /**
     * Theme constructor.
     * Initializes the collection of Cursus entities.
     */
    public function __construct()
    {
        $this->cursuses = new ArrayCollection();
    }

    /**
     * Get the ID of the theme.
     *
     * @return int|null The ID of the theme.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the name of the theme.
     *
     * @return string|null The name of the theme.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name of the theme.
     *
     * @param string $name The name to set.
     * @return static The current instance.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the collection of Cursus entities related to this theme.
     *
     * @return Collection<int, Cursus> A collection of Cursus entities.
     */
    public function getCursuses(): Collection
    {
        return $this->cursuses;
    }

    /**
     * Add a Cursus to this theme.
     *
     * @param Cursus $cursus The Cursus entity to add.
     * @return static The current instance.
     */
    public function addCursus(Cursus $cursus): static
    {
        if (!$this->cursuses->contains($cursus)) {
            $this->cursuses->add($cursus);
            $cursus->setTheme($this);
        }

        return $this;
    }

    /**
     * Remove a Cursus from this theme.
     *
     * @param Cursus $cursus The Cursus entity to remove.
     * @return static The current instance.
     */
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

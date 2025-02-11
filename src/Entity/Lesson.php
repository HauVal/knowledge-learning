<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** 
     * @var string|null The name of the lesson 
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /** 
     * @var float|null The price of the lesson 
     */
    #[ORM\Column]
    private ?float $price = null;

    /** 
     * @var Cursus|null The cursus to which this lesson belongs 
     */
    #[ORM\ManyToOne(inversedBy: 'lessons')]
    private ?Cursus $cursus = null;

    /**
     * @var Collection<int, Purchase> Collection of purchases related to this lesson
     */
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'lesson')]
    private Collection $purchases;

    public function __construct()
    {
        $this->purchases = new ArrayCollection();
    }

    /**
     * Get the lesson ID
     * 
     * @return int|null The lesson ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the lesson name
     * 
     * @return string|null The lesson name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the lesson name
     * 
     * @param string $name The lesson name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the lesson price
     * 
     * @return float|null The lesson price
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Set the lesson price
     * 
     * @param float $price The lesson price
     * @return static
     */
    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the associated cursus
     * 
     * @return Cursus|null The associated cursus
     */
    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    /**
     * Set the associated cursus
     * 
     * @param Cursus|null $cursus The cursus entity
     * @return static
     */
    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }

    /**
     * Get all purchases related to this lesson
     * 
     * @return Collection<int, Purchase>
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    /**
     * Add a purchase to the lesson
     * 
     * @param Purchase $purchase The purchase entity
     * @return static
     */
    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setLesson($this);
        }

        return $this;
    }

    /**
     * Remove a purchase from the lesson
     * 
     * @param Purchase $purchase The purchase entity
     * @return static
     */
    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            // Set the owning side to null (unless already changed)
            if ($purchase->getLesson() === $this) {
                $purchase->setLesson(null);
            }
        }

        return $this;
    }
}

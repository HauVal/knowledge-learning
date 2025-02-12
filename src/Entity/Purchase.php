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

    /**
     * The user who made the purchase.
     *
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The purchased cursus (if applicable).
     *
     * @var Cursus|null
     */
    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?Cursus $cursus = null;

    /**
     * The purchased lesson (if applicable).
     *
     * @var Lesson|null
     */
    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?Lesson $lesson = null;

    /**
     * The date when the purchase was made.
     *
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $purchase_date = null;

    /**
     * Get the purchase ID.
     *
     * @return int|null The purchase ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the user who made the purchase.
     *
     * @return User|null The user entity
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the user who made the purchase.
     *
     * @param User|null $user The user entity
     * @return static
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the purchased cursus (if any).
     *
     * @return Cursus|null The purchased cursus entity
     */
    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    /**
     * Set the purchased cursus.
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
     * Get the purchased lesson (if any).
     *
     * @return Lesson|null The purchased lesson entity
     */
    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    /**
     * Set the purchased lesson.
     *
     * @param Lesson|null $lesson The lesson entity
     * @return static
     */
    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get the purchase date.
     *
     * @return \DateTimeInterface|null The purchase date
     */
    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchase_date;
    }

    /**
     * Set the purchase date.
     *
     * @param \DateTimeInterface $purchase_date The date of the purchase
     * @return static
     */
    public function setPurchaseDate(\DateTimeInterface $purchase_date): static
    {
        $this->purchase_date = $purchase_date;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;



#[UniqueEntity('name')]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $name = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(1441)]
    private ?int $time;

    #[Assert\Positive()]
    #[Assert\LessThan(51)]
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nbPeople = null;

    #[Assert\Positive()]
    #[Assert\LessThan(6)]
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $difficulty = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[Assert\Positive()]
    #[Assert\LessThan(100)]
    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $price = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isFavorite = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $timeCreate = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $timeUpdate = null;

    #[ORM\ManyToMany(targetEntity: Ingredients::class)]
    private Collection $ingredients;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->timeCreate = new DateTimeImmutable();
        $this->timeUpdate = new DateTimeImmutable();
    }

    #[ORM\PrePersist()]
    public function setTimeUpdateValue() {
        $this->timeUpdate = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getNbPeople(): ?int
    {
        return $this->nbPeople;
    }

    public function setNbPeople(?int $nbPeople): static
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    public function getdifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setdifficulty(?int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): static
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function gettimeCreate(): ?\DateTimeImmutable
    {
        return $this->timeCreate;
    }

    public function setTimeCreate(\DateTimeImmutable $timeCreate): static
    {
        $this->timeCreate = $timeCreate;

        return $this;
    }

    public function getTimeUpdate(): ?\DateTimeImmutable
    {
        return $this->timeUpdate;
    }

    public function setTimeUpdate(\DateTimeImmutable $timeUpdate): static
    {
        $this->timeUpdate = $timeUpdate;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}

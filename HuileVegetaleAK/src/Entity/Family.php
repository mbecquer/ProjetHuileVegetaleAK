<?php

namespace App\Entity;

use App\Repository\FamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FamilyRepository::class)
 */
class Family
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="text")
     */
    private $story;

    /**
     * @ORM\OneToMany(targetEntity=Oil::class, mappedBy="family",orphanRemoval=true)
     */
    private $oil;

    public function __construct()
    {
        $this->oil = new ArrayCollection();
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getStory(): ?string
    {
        return $this->story;
    }

    public function setStory(string $story): self
    {
        $this->story = $story;

        return $this;
    }

    /**
     * @return Collection|Oil[]
     */
    public function getOil(): Collection
    {
        return $this->oil;
    }

    public function addOil(Oil $oil): self
    {
        if (!$this->oil->contains($oil)) {
            $this->oil[] = $oil;
            $oil->setFamily($this);
        }

        return $this;
    }

    public function removeOil(Oil $oil): self
    {
        if ($this->oil->removeElement($oil)) {
            // set the owning side to null (unless already changed)
            if ($oil->getFamily() === $this) {
                $oil->setFamily(null);
            }
        }

        return $this;
    }
}

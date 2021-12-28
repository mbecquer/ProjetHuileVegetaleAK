<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=ImageArticle::class, mappedBy="article", orphanRemoval=true)
     */
    private $imageArticles;



    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->imageArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|ImageArticle[]
     */
    public function getImageArticles(): Collection
    {
        return $this->imageArticles;
    }

    public function addImageArticle(ImageArticle $imageArticle): self
    {
        if (!$this->imageArticles->contains($imageArticle)) {
            $this->imageArticles[] = $imageArticle;
            $imageArticle->setArticle($this);
        }

        return $this;
    }

    public function removeImageArticle(ImageArticle $imageArticle): self
    {
        if ($this->imageArticles->removeElement($imageArticle)) {
            // set the owning side to null (unless already changed)
            if ($imageArticle->getArticle() === $this) {
                $imageArticle->setArticle(null);
            }
        }

        return $this;
    }

  
}

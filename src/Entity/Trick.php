<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le titre du trick ne peut pas être vide')]
    #[Assert\Length(
        min: 5,
        max: 200,
        minMessage: 'Le titre du trick doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le titre du trick doit contenir au maximum {{ limit }} caractères'
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 3000)]
    #[Assert\NotBlank(message: 'La description du trick ne peut pas être vide')]
    #[Assert\Length(
        min: 10,
        max: 3000,
        minMessage: 'La description du trick doit contenir au moins {{ limit }} caractères',
        maxMessage: 'La description du trick doit contenir au maximum {{ limit }} caractères'
    )]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'trick')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrickCategory $category = null;

    #[ORM\ManyToOne(inversedBy: 'trick')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class)]
    private Collection $comment;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickImage::class, orphanRemoval: true, cascade: ['persist'])]
    private $image;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickVideo::class, orphanRemoval: true, cascade: ['persist'])]
    private $video;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        $this->comment = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->video = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?TrickCategory
    {
        return $this->category;
    }

    public function setCategory(?TrickCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrickImage>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(TrickImage $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setTrick($this);
        }

        return $this;
    }

    public function removeImage(TrickImage $image): self
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getTrick() === $this) {
                $image->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrickVideo>
     */
    public function getVideo(): Collection
    {
        return $this->video;
    }

    public function addVideo(TrickVideo $video): self
    {
        if (!$this->video->contains($video)) {
            $this->video->add($video);
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(TrickVideo $video): self
    {
        if ($this->video->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

}

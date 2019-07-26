<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @Table(
 *      name = "commentary",  
 *      uniqueConstraints = {@UniqueConstraint(columns = {"user_id", "game_id"})}
 * )
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"game_read", "comment_read", "game_info"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"game_read", "comment_read", "game_info"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"game_read", "comment_read", "game_info"})
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"game_read", "comment_read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"game_read", "comment_read"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="comments", cascade={"persist"})
     * @Groups({"comment_read"})
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments", cascade={"persist"})
     * @Groups({"game_read", "comment_read"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentLike", mappedBy="comment")
     */
    private $commentLikes;

    public function __construct()
    {
        $this->isActive = true;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->commentLikes = new ArrayCollection();
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|CommentLike[]
     */
    public function getCommentLikes(): Collection
    {
        return $this->commentLikes;
    }

    public function addCommentLike(CommentLike $commentLike): self
    {
        if (!$this->commentLikes->contains($commentLike)) {
            $this->commentLikes[] = $commentLike;
            $commentLike->setComment($this);
        }

        return $this;
    }

    public function removeCommentLike(CommentLike $commentLike): self
    {
        if ($this->commentLikes->contains($commentLike)) {
            $this->commentLikes->removeElement($commentLike);
            // set the owning side to null (unless already changed)
            if ($commentLike->getComment() === $this) {
                $commentLike->setComment(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }


    public function isCommentNonLocked()
    {
        return true;
    }


    public function isEnabled()
    {
        return $this->isActive;
    }
}

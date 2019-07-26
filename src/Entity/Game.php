<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"game_read", "category_games", "comment_read", "status_read", "game_info"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"game_read", "category_games", "comment_read", "status_read", "game_info"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"game_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     * @Groups({"game_read", "status_read"})
     */
    private $score;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"game_read", "status_read", "game_info"})
     */
    private $cover;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"game_read"})
     */
    private $illustration;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     * @Groups({"game_read"})
     */
    private $pegi;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"game_read"})
     */
    private $website;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"game_read"})
     */
    private $releasedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="games", cascade={"persist"})
     * @Groups({"game_read"})
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Developer", inversedBy="games", cascade={"persist"})
     * @Groups({"game_read"})
     */
    private $developers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Editor", inversedBy="games", cascade={"persist"})
     * @Groups({"game_read"})
     */
    private $editors;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Platform", inversedBy="games", cascade={"persist"})
     * @Groups({"game_read"})
     */
    private $platforms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="game", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Score", mappedBy="game", cascade={"remove"})
     */
    private $scores;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\State", mappedBy="game", cascade={"remove"})
     */
    private $states;

    public function __construct()
    {   
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->categories = new ArrayCollection();
        $this->developers = new ArrayCollection();
        $this->editors = new ArrayCollection();
        $this->platforms = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->scores = new ArrayCollection();
        $this->states = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(?string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function getPegi(): ?int
    {
        return $this->pegi;
    }

    public function setPegi(?int $pegi): self
    {
        $this->pegi = $pegi;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeInterface
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(?\DateTimeInterface $releasedAt): self
    {
        $this->releasedAt = $releasedAt;

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

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Developer[]
     */
    public function getDevelopers(): Collection
    {
        return $this->developers;
    }

    public function addDeveloper(Developer $developer): self
    {
        if (!$this->developers->contains($developer)) {
            $this->developers[] = $developer;
        }

        return $this;
    }

    public function removeDeveloper(Developer $developer): self
    {
        if ($this->developers->contains($developer)) {
            $this->developers->removeElement($developer);
        }

        return $this;
    }

    /**
     * @return Collection|Editor[]
     */
    public function getEditors(): Collection
    {
        return $this->editors;
    }

    public function addEditor(Editor $editor): self
    {
        if (!$this->editors->contains($editor)) {
            $this->editors[] = $editor;
        }

        return $this;
    }

    public function removeEditor(Editor $editor): self
    {
        if ($this->editors->contains($editor)) {
            $this->editors->removeElement($editor);
        }

        return $this;
    }

    /**
     * @return Collection|Platform[]
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platforms->contains($platform)) {
            $this->platforms->removeElement($platform);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setGame($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getGame() === $this) {
                $comment->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setGame($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getGame() === $this) {
                $score->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|State[]
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    public function addState(State $state): self
    {
        if (!$this->states->contains($state)) {
            $this->states[] = $state;
            $state->setGame($this);
        }

        return $this;
    }

    public function removeState(State $state): self
    {
        if ($this->states->contains($state)) {
            $this->states->removeElement($state);
            // set the owning side to null (unless already changed)
            if ($state->getGame() === $this) {
                $state->setGame(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
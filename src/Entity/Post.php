<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
*/
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
{
    return $this->id;
}

    public function getText(): ?string
{
    return $this->text;
}

    public function setText(string $text): self
{
    $this->text = $text;

    return $this;
}

    public function getTitle(): ?string
{
    return $this->title;
}

    public function setTitle(?string $title): self
{
    $this->title = $title;

    return $this;
}

    public function getPhoto(): ?string
{
    return $this->photo;
}

    public function setPhoto(?string $photo): self
{
    $this->photo = $photo;

    return $this;
}

    public function getRating(): ?int
{
    return $this->rating;
}

    public function setRating(?int $rating): self
{
    $this->rating = $rating;

    return $this;
}

    public function getCreatedAt(): ?\DateTimeInterface
{
    return $this->created_at;
}

    public function setCreatedAt(?\DateTimeInterface $created_at): self
{
    $this->created_at = $created_at;

    return $this;
}
}

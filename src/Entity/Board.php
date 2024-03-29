<?php

namespace App\Entity;

use App\Transformers\BoardTransformer;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;

class Board
{
    /**
     * @var string
     * @Exclude(if="true")
     */
    public $transformer = BoardTransformer::class;

    /**
     * @var $id
     */
    private $id;

    private $name;

    private $createdAt;

    private $updatedAt;

    /**
     * @Groups({"user"})
     * @var User
     */
    private $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTransformer(){
        return $this->transformer;
    }
}

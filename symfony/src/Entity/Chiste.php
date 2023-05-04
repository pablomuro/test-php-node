<?php

namespace App\Entity;

use App\Repository\ChisteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChisteRepository::class)]
class Chiste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $joke = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoke(): ?string
    {
        return $this->joke;
    }

    public function setJoke(string $joke): self
    {
        $this->joke = $joke;

        return $this;
    }
}

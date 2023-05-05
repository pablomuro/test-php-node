<?php

namespace App\Entity;

use App\Repository\JokeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JokeRepository::class)]
class Joke
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $jokeText = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJokeText(): ?string
    {
        return $this->jokeText;
    }

    public function setJokeText(string $jokeText): self
    {
        $this->jokeText = $jokeText;

        return $this;
    }

    public function toJson(){
        return [
            'id' => $this->getId(),
            'jokeText' => $this->getJokeText()
        ];
    }

}

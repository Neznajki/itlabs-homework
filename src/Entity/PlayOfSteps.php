<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * PlayOfSteps
 *
 * @ORM\Table(name="play_of_steps", uniqueConstraints={@ORM\UniqueConstraint(name="play_of_steps_pk_2", columns={"name"}), @ORM\UniqueConstraint(name="play_of_steps_name_uindex", columns={"name"})})
 * @ORM\Entity(repositoryClass="App\Repository\PlayOfStepsRepository")
 */
class PlayOfSteps implements JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="match_count", type="integer", nullable=false)
     */
    private $matchCount;

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

    public function getMatchCount(): ?int
    {
        return $this->matchCount;
    }

    public function setMatchCount(int $matchCount): self
    {
        $this->matchCount = $matchCount;

        return $this;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'matchCount' => $this->getMatchCount(),
        ];
    }
}

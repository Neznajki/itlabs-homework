<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Table(name: 'challenge_division', uniqueConstraints: [new ORM\UniqueConstraint(name: 'challenge_division_pk_2', columns: ['challenge_id', 'division_id'])], indexes: [new ORM\Index(name: 'challenge_division_division_id_fk', columns: ['division_id']), new ORM\Index(name: 'IDX_8D29A0F498A21AC6', columns: ['challenge_id'])])]
#[ORM\Entity(repositoryClass: \App\Repository\ChallengeDivisionRepository::class)]
class ChallengeDivision implements JsonSerializable
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var Challenge
     */
    #[ORM\ManyToOne(targetEntity: Challenge::class)]
    #[ORM\JoinColumn(name: 'challenge_id', referencedColumnName: 'id')]
    private $challenge;

    /**
     * @var Division
     */
    #[ORM\ManyToOne(targetEntity: Division::class)]
    #[ORM\JoinColumn(name: 'division_id', referencedColumnName: 'id')]
    private $division;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): self
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function getDivision(): ?Division
    {
        return $this->division;
    }

    public function setDivision(?Division $division): self
    {
        $this->division = $division;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getChallenge(), $this->getDivision());
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'challenge' => $this->getChallenge(),
            'division' => $this->getDivision(),
        ];
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChallengeDivision
 *
 * @ORM\Table(name="challenge_division", uniqueConstraints={@ORM\UniqueConstraint(name="challenge_division_pk_2", columns={"challenge_id", "division_id"})}, indexes={@ORM\Index(name="challenge_division_division_id_fk", columns={"division_id"}), @ORM\Index(name="IDX_8D29A0F498A21AC6", columns={"challenge_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ChallengeDivisionRepository")
 */
class ChallengeDivision
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
     * @var Challenge
     *
     * @ORM\ManyToOne(targetEntity="Challenge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     * })
     */
    private $challenge;

    /**
     * @var Division
     *
     * @ORM\ManyToOne(targetEntity="Division")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="division_id", referencedColumnName="id")
     * })
     */
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


}

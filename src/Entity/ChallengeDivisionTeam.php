<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * ChallengeDivisionTeam
 *
 * @ORM\Table(name="challenge_division_team", uniqueConstraints={@ORM\UniqueConstraint(name="challenge_division_team_team_id_uindex", columns={"team_id"}), @ORM\UniqueConstraint(name="challenge_division_team_challenge_division_id_uindex", columns={"challenge_division_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ChallengeDivisionTeamRepository")
 */
class ChallengeDivisionTeam
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
     * @var DateTime|null
     *
     * @ORM\Column(name="assigned", type="datetime", nullable=true)
     */
    private $assigned;

    /**
     * @var ChallengeDivision
     *
     * @ORM\ManyToOne(targetEntity="ChallengeDivision")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="challenge_division_id", referencedColumnName="id")
     * })
     */
    private $challengeDivision;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssigned(): ?DateTimeInterface
    {
        return $this->assigned;
    }

    public function setAssigned(?DateTimeInterface $assigned): self
    {
        $this->assigned = $assigned;

        return $this;
    }

    public function getChallengeDivision(): ?ChallengeDivision
    {
        return $this->challengeDivision;
    }

    public function setChallengeDivision(?ChallengeDivision $challengeDivision): self
    {
        $this->challengeDivision = $challengeDivision;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }


}

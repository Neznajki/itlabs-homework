<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * DivisionMatch
 *
 * @ORM\Table(name="division_match", uniqueConstraints={@ORM\UniqueConstraint(name="division_match_pk", columns={"team_a_id", "team_b_id"})}, indexes={@ORM\Index(name="division_match_challenge_division_team_id_fk_2", columns={"team_b_id"}), @ORM\Index(name="IDX_531F211BEA3FA723", columns={"team_a_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\DivisionMatchRepository")
 */
class DivisionMatch implements JsonSerializable
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
     * @var bool|null
     *
     * @ORM\Column(name="team_a_win", type="boolean", nullable=true)
     */
    private $teamAWin;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="resulted", type="datetime", nullable=true)
     */
    private $resulted;

    /**
     * @var ChallengeDivisionTeam
     *
     * @ORM\ManyToOne(targetEntity="ChallengeDivisionTeam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_a_id", referencedColumnName="id")
     * })
     */
    private $teamA;

    /**
     * @var ChallengeDivisionTeam
     *
     * @ORM\ManyToOne(targetEntity="ChallengeDivisionTeam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_b_id", referencedColumnName="id")
     * })
     */
    private $teamB;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamAWin(): ?bool
    {
        return $this->teamAWin;
    }

    public function setTeamAWin(?bool $teamAWin): self
    {
        $this->teamAWin = $teamAWin;

        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getResulted(): ?DateTimeInterface
    {
        return $this->resulted;
    }

    public function setResulted(?DateTimeInterface $resulted): self
    {
        $this->resulted = $resulted;

        return $this;
    }

    public function getTeamA(): ?ChallengeDivisionTeam
    {
        return $this->teamA;
    }

    public function setTeamA(?ChallengeDivisionTeam $teamA): self
    {
        $this->teamA = $teamA;

        return $this;
    }

    public function getTeamB(): ?ChallengeDivisionTeam
    {
        return $this->teamB;
    }

    public function setTeamB(?ChallengeDivisionTeam $teamB): self
    {
        $this->teamB = $teamB;

        return $this;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'teamA' => $this->getTeamA(),
            'teamB' => $this->getTeamB(),
            'teamAWin' => $this->getTeamAWin(),
            'created' => $this->getCreated(),
            'resulted' => $this->getResulted(),
        ];
    }
}

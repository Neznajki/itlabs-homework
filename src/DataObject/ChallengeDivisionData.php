<?php
declare(strict_types=1);


namespace App\DataObject;


use App\Entity\ChallengeDivision;
use App\Entity\ChallengeDivisionTeam;
use App\Entity\DivisionMatch;

class ChallengeDivisionData extends AbstractScoreCounter
{
    /** @var ChallengeDivision */
    private $challengeDivision;
    /** @var ChallengeDivisionTeam[] */
    private $teams;
    /** @var DivisionMatch[] */
    private $divisionMatches;

    /**
     * ChallengeDivisionData constructor.
     * @param ChallengeDivision $challengeDivision
     * @param ChallengeDivisionTeam[] $teams
     */
    public function __construct(ChallengeDivision $challengeDivision, array $teams)
    {
        $this->challengeDivision = $challengeDivision;
        $this->teams = $teams;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->challengeDivision;
    }

    /**
     * @return ChallengeDivisionTeam[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return DivisionMatch[]
     */
    public function getMatches(): array
    {
        return $this->divisionMatches;
    }

    /**
     * @return bool
     */
    public function haveMatches(): bool
    {
        foreach ($this->getMatches() as $match) {
            if ($match->getTeamAWin() === null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ChallengeDivisionTeam $teamA
     * @param ChallengeDivisionTeam $teamB
     * @return DivisionMatch|null
     */
    public function getMatch(ChallengeDivisionTeam $teamA, ChallengeDivisionTeam $teamB): ?DivisionMatch
    {
        foreach ($this->getMatches() as $divisionMatch) {
            if (
                $teamA->getId() == $divisionMatch->getTeamA()->getId() &&
                $teamB->getId() == $divisionMatch->getTeamB()->getId()
            ) {
                return $divisionMatch;
            }
        }

        return null;
    }

    /**
     * @param DivisionMatch[] $divisionMatches
     */
    public function setMatches(array $divisionMatches)
    {
        $this->divisionMatches = $divisionMatches;
    }
}
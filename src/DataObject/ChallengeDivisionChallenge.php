<?php
declare(strict_types=1);


namespace App\DataObject;


use App\Entity\ChallengeDivision;
use App\Entity\ChallengeDivisionTeam;
use App\Entity\DivisionMatch;
use RuntimeException;

class ChallengeDivisionChallenge
{
    /** @var ChallengeDivisionData[] */
    protected $challengeDivisions = [];

    /**
     * @return ChallengeDivisionData[]
     */
    public function getChallengeDivisions(): array
    {
        return $this->challengeDivisions;
    }

    /**
     * @return bool
     */
    public function haveDivisionMatch():bool
    {
        foreach ($this->getChallengeDivisions() as $challengeDivision) {
            if ($challengeDivision->haveMatches()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param ChallengeDivision $division
     * @param ChallengeDivisionTeam[] $teams
     */
    public function setDivisionTeams(ChallengeDivision $division, array $teams)
    {
        $this->challengeDivisions[$division->getId()] = new ChallengeDivisionData($division, $teams);
    }

    /**
     * @param ChallengeDivision $division
     * @param DivisionMatch[] $divisionMatch
     */
    public function setDivisionMatches(ChallengeDivision $division, array $divisionMatch)
    {
        $this->getDivisionData($division)->setMatches($divisionMatch);
    }

    /**
     * @param ChallengeDivision $division
     * @return ChallengeDivisionData
     */
    public function getDivisionData(ChallengeDivision $division): ChallengeDivisionData
    {
        if (empty($this->challengeDivisions[$division->getId()])) {
            throw new RuntimeException('could not find challenge division data, call set Division Teams before');
        }

        return $this->challengeDivisions[$division->getId()];
    }
}
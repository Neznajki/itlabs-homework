<?php
declare(strict_types=1);


namespace App\DataObject;


use App\Entity\ChallengeDivisionTeam;
use App\Entity\DivisionMatch;

abstract class AbstractScoreCounter
{
    /**
     * @param ChallengeDivisionTeam $team
     * @return int
     */
    public function getScore(ChallengeDivisionTeam $team): int
    {
        $result = 0;

        foreach ($this->getMatches() as $divisionMatch) {
            if (
                $divisionMatch->getTeamA()->getId() == $team->getId() &&
                $divisionMatch->getTeamAWin() === true
            ) {
                $result ++;
            } elseif(
                $divisionMatch->getTeamB()->getId() == $team->getId() &&
                $divisionMatch->getTeamAWin() === false
            ) {
                $result ++;
            }
        }

        return $result;
    }

    /**
     * @return DivisionMatch[]
     */
    abstract protected function getMatches(): array ;
}
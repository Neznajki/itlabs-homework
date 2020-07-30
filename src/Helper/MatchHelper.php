<?php
declare(strict_types=1);


namespace App\Helper;


use App\Entity\ChallengeDivisionTeam;
use App\Entity\PlayOfMatch;
use RuntimeException;

class MatchHelper
{
    /**
     * @param ChallengeDivisionTeam $teamA
     * @param ChallengeDivisionTeam $teamB
     * @return bool
     */
    public static function predictTeamAWin(
        ChallengeDivisionTeam $teamA,
        ChallengeDivisionTeam $teamB
    ): bool {
        $teamAStrength = $teamA->getTeam()->getStrength();
        $maxNumber = $teamAStrength + $teamB->getTeam()->getStrength();
        $random = rand(1, $maxNumber);

        if ($random <= $teamAStrength) {
            return true;
        }

        return false;
    }

    /**
     * @param PlayOfMatch $playOfMatch
     * @return ChallengeDivisionTeam|null
     */
    public static function getWinnerInPlayOff(
        PlayOfMatch $playOfMatch
    ) {
        if ($playOfMatch->getTeamAWin() === null) {
            throw new RuntimeException("can't get winner in not predicted match {$playOfMatch->getId()}");
        }

        return $playOfMatch->getTeamAWin() ? $playOfMatch->getTeamA() : $playOfMatch->getTeamB();
    }
}
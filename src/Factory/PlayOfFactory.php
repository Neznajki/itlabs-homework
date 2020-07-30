<?php
declare(strict_types=1);


namespace App\Factory;


use App\DataObject\DivisionMatches;
use App\DataObject\PlayOfCollection;
use App\DataObject\PlayOfPotential;
use App\Entity\PlayOfMatch;
use App\Entity\PlayOfSteps;

class PlayOfFactory
{
    /**
     * @param PlayOfSteps $playOfStep
     * @param array $matches
     * @param int $divisionsCount
     * @return PlayOfMatch[]
     */
    public static function createPlayOfByDivisionMatches(PlayOfSteps $playOfStep, array $matches, int $divisionsCount = 2): array
    {
        $result = [];

        $divisionMatches = new DivisionMatches($matches);

        $divisions = $divisionMatches->getDivisions();

        $playOfCollection = new PlayOfCollection();
        $divisionPlayersCount = $playOfStep->getMatchCount() / $divisionsCount;//could be /4 in case 4 divisions

        foreach ($divisions as $divisionId) {
            $divisionMatches->isEnoughTeams($divisionId, $divisionPlayersCount * $divisionsCount);

            $playOfPotential = new PlayOfPotential($divisionPlayersCount);
            $playOfCollection->addPlayOfData($divisionId, $playOfPotential);

            $playOfPotential->setPotentialWinners(
                $divisionMatches->getPotentialBestTeams($divisionId, $divisionPlayersCount)
            );
            $playOfPotential->tieBreakWinners();
            $divisionMatches->removeFromPool($divisionId, $playOfPotential->getWinners());

            $playOfPotential->setPotentialLosers(
                $divisionMatches->getPotentialBestTeams($divisionId, $divisionPlayersCount)
            );
            $playOfPotential->tieBreakLosers();
            $divisionMatches->removeFromPool($divisionId, $playOfPotential->getLosers());
        }

        $matchPos = 1;
        while ($playOfMatch = $playOfCollection->createPlayOfMatchEntity()) {
            $playOfMatch->setPlayOfStep($playOfStep);
            $playOfMatch->setMatchPos($matchPos);

            $matchPos++;
            $result[] = $playOfMatch;
        }

        return $result;
    }
}
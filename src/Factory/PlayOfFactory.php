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
     * @return PlayOfMatch[]
     */
    public static function createPlayOfByDivisionMatches(PlayOfSteps $playOfStep, array $matches): array
    {
        $result = [];

        $divisionMatches = new DivisionMatches($matches);

        $divisions = $divisionMatches->getDivisions();

        $playOfCollection = new PlayOfCollection();
        $divisionPlayersCount = $playOfStep->getMatchCount();//could be /2 in case 4 divisions

        foreach ($divisions as $divisionId) {
            $playOfPotential = new PlayOfPotential($playOfStep);
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

        while ($playOfMatch = $playOfCollection->createPlayOfMatchEntity()) {
            $result[] = $playOfMatch;
        }

        return $result;
    }
}
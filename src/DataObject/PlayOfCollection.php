<?php
declare(strict_types=1);


namespace App\DataObject;


use App\Entity\PlayOfMatch;
use RuntimeException;

class PlayOfCollection
{
    /** @var PlayOfPotential[]  */
    protected array $playOfCollection = [];

    /**
     * @param int $divisionId
     * @param PlayOfPotential $playOfPotential
     */
    public function addPlayOfData(int $divisionId, PlayOfPotential $playOfPotential)
    {
        $this->playOfCollection[$divisionId] = $playOfPotential;
    }

    /**
     * @return PlayOfMatch|null
     */
    public function createPlayOfMatchEntity(): ?PlayOfMatch
    {
        if (empty($this->playOfCollection)) {
            return null;
        }

        $result = new PlayOfMatch();

        $randDivisionWin = $this->getRandomWinnerDivisionId();
        $randDivisionLose = $this->getRandomLoserDivisionId($randDivisionWin);

        $winnerPotential = $this->playOfCollection[$randDivisionWin];
        $winners = $winnerPotential->getWinners();
        $loserPotential = $this->playOfCollection[$randDivisionLose];
        $losers = $loserPotential->getLosers();
        $randomTeamA = array_rand($winners);
        $randomTeamB = array_rand($losers);

        $result->setTeamA($winners[$randomTeamA]);
        $winnerPotential->removeWinner($randomTeamA);

        $result->setTeamB($losers[$randomTeamB]);
        $loserPotential->removeLoser($randomTeamB);

        if (
            empty($loserPotential->getLosers()) &&
            empty($loserPotential->getWinners())
        ) {
            unset($this->playOfCollection[$randDivisionLose]);
        }

        if (
            empty($winnerPotential->getLosers()) &&
            empty($winnerPotential->getWinners())
        ) {
            unset($this->playOfCollection[$randDivisionWin]);
        }

        return $result;
    }

    /**
     * @return int
     */
    protected function getRandomWinnerDivisionId(): int
    {
        $pick = [];
        foreach ($this->playOfCollection as $divisionId => $potential) {
            if (empty($potential->getWinners())) {
                continue;
            }

            $pick[$divisionId] = $potential->getWinners();
        }

        if (empty($pick)) {
            throw new RuntimeException("could not find winner division");
        }

        return array_rand($pick);
    }

    /**
     * @param int $excluded
     * @return int
     */
    protected function getRandomLoserDivisionId(int $excluded): int
    {
        $pick = [];
        foreach ($this->playOfCollection as $divisionId => $potential) {
            if ($divisionId === $excluded || empty($potential->getLosers())) {
                continue;
            }

            $pick[$divisionId] = $potential->getLosers();
        }

        if (empty($pick)) {
            throw new RuntimeException("could not find loser division");
        }

        return array_rand($pick);
    }
}
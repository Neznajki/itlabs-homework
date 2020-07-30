<?php
declare(strict_types=1);


namespace App\DataObject;


use App\Entity\ChallengeDivisionTeam;
use App\Entity\PlayOfSteps;

class PlayOfPotential
{
    /** @var ChallengeDivisionTeam[]  */
    protected array $winners;
    /** @var ChallengeDivisionTeam[]  */
    protected array $losers;

    /** @var ChallengeDivisionTeam[][]  */
    protected array $potentialWinners;
    /** @var ChallengeDivisionTeam[][]  */
    protected array $potentialLosers;
    /** @var PlayOfSteps */
    private PlayOfSteps $playOfSteps;

    /**
     * PlayOfPotential constructor.
     * @param PlayOfSteps $playOfSteps
     */
    public function __construct(PlayOfSteps $playOfSteps)
    {
        $this->playOfSteps = $playOfSteps;
    }

    /**
     * @return ChallengeDivisionTeam[]
     */
    public function getWinners(): array
    {
        return $this->winners;
    }

    /**
     * @param int $key
     */
    public function removeWinner(int $key): void
    {
        unset($this->winners[$key]);
    }

    /**
     * @return ChallengeDivisionTeam[]
     */
    public function getLosers(): array
    {
        return $this->losers;
    }

    /**
     * @param int $key
     */
    public function removeLoser(int $key): void
    {
        unset($this->losers[$key]);
    }

    /**
     *
     */
    public function tieBreakWinners()
    {
        $this->winners = $this->tieBreakChose($this->potentialWinners);
    }

    /**
     * @param ChallengeDivisionTeam[] $potentialWinners
     */
    public function setPotentialWinners(array $potentialWinners): void
    {
        $this->potentialWinners = $potentialWinners;
    }

    /**
     *
     */
    public function tieBreakLosers()
    {
        $this->losers = $this->tieBreakChose($this->potentialLosers);
    }

    /**
     * @param ChallengeDivisionTeam[] $potentialLosers
     */
    public function setPotentialLosers(array $potentialLosers): void
    {
        $this->potentialLosers = $potentialLosers;
    }

    /**
     * could be some additional matching or whatever
     *
     * @param ChallengeDivisionTeam[] $teams
     * @param int $required
     * @return ChallengeDivisionTeam[]
     */
    public function tieBreak(array $teams, int $required): array
    {
        if (count($teams) == $required) {
            return $teams;
        }
        $result = [];

        foreach ($teams as $team) {
            $required--;
            $result[] = $team;

            if ($required === 0) {
                break;
            }
        }

        return $result;
    }

    /**
     * @param $tmpPotentialWinners
     * @return array
     */
    protected function tieBreakChose($tmpPotentialWinners): array
    {
        $chosen = [];

        while (!empty($tmpPotentialWinners)) {
            $teams = array_shift($tmpPotentialWinners);

            if (empty($tmpPotentialWinners)) {
                $teams = $this->tieBreak($teams, $this->playOfSteps->getMatchCount() - count($chosen));
            }

            foreach ($teams as $team) {
                $chosen[$team->getId()] = $team;
            }
        }

        return $chosen;
    }
}
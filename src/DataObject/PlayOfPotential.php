<?php
declare(strict_types=1);


namespace App\DataObject;


use App\Entity\ChallengeDivisionTeam;

class PlayOfPotential
{
    /** @var ChallengeDivisionTeam[] */
    protected array $winners;
    /** @var ChallengeDivisionTeam[] */
    protected array $losers;

    /** @var ChallengeDivisionTeam[][] */
    protected array $potentialWinners;
    /** @var ChallengeDivisionTeam[][] */
    protected array $potentialLosers;
    private int $teamsRequired;

    /**
     * PlayOfPotential constructor.
     * @param int $teamsRequired
     */
    public function __construct(int $teamsRequired)
    {
        $this->teamsRequired = $teamsRequired;
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
        if (count($teams) <= $required) {
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
            $teams = $this->tieBreak(
                array_shift($tmpPotentialWinners),
                $this->teamsRequired - count($chosen)
            );

            foreach ($teams as $team) {
                $chosen[$team->getId()] = $team;
            }
        }

        return $chosen;
    }
}
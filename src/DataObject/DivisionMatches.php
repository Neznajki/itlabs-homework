<?php
declare(strict_types=1);


namespace App\DataObject;


use App\Entity\ChallengeDivisionTeam;
use App\Entity\DivisionMatch;
use App\Entity\Team;
use RuntimeException;

class DivisionMatches extends AbstractScoreCounter
{
    /** @var DivisionMatch[] */
    protected array $matches;
    /** @var Team[][] */
    protected array $sortedDivisionTeams = [];
    /** @var int[] */
    protected array $scores = [];
    /** @var Team[][]  */
    protected array $teamsByScores;

    /**
     * DivisionMatches constructor.
     * @param DivisionMatch[] $matches
     */
    public function __construct(array $matches)
    {
        $this->matches = $matches;

        $this->sortMatches($matches);
    }

    /**
     * @return DivisionMatch[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @return int[]
     */
    public function getDivisions(): array
    {
        return array_keys($this->sortedDivisionTeams);
    }


    /**
     * @param int $divisionId
     * @param int $required
     */
    public function isEnoughTeams(int $divisionId, int $required)
    {
        if (count($this->sortedDivisionTeams[$divisionId]) < $required) {
            throw new RuntimeException('not enough team for play of calculation');
        }
    }

    /**
     * @param int $divisionId
     * @param int $count
     * @return array
     */
    public function getPotentialBestTeams(int $divisionId, int $count) :array
    {
        $result = [];
        $found = 0;

        foreach ($this->teamsByScores[$divisionId] as $score => $teamsByScore) {
            $result[$score] = $teamsByScore;
            $found += count($teamsByScore);

            if ($found >= $count) {
                break;
            }
        }

        return $result;
    }

    /**
     * @param int $divisionId
     * @param ChallengeDivisionTeam[] $teams
     */
    public function removeFromPool(int $divisionId, array $teams)
    {
        foreach ($teams as $team) {
            $found = false;
            foreach ($this->teamsByScores[$divisionId] as &$item) {

                $id = $team->getId();
                if (array_key_exists($id, $item)) {
                    unset($item[$id]);
                }
            }

            if ($found) {
                break;
            }
        }

        $this->teamsByScores = array_filter($this->teamsByScores);
    }

    /**
     * @param DivisionMatch[] $matches
     */
    protected function sortMatches(array $matches): void
    {
        foreach ($matches as $match) {
            $divisionId = $match->getTeamA()->getChallengeDivision()->getId();

            if (empty($this->sortedDivisionTeams[$divisionId])) {
                $this->sortedDivisionTeams[$divisionId] = [];
                $this->teamsByScores[$divisionId] = [];
            }

            if (
                array_key_exists($match->getTeamA()->getId(), $this->sortedDivisionTeams[$divisionId])
            ) {
                if (
                    array_key_exists($match->getTeamB()->getId(), $this->sortedDivisionTeams[$divisionId])
                ) {
                    continue;
                }
                $this->addTeam($match->getTeamB(), $divisionId);

                continue;
            }

            $this->addTeam($match->getTeamA(), $divisionId);
        }

        foreach ($this->sortedDivisionTeams as &$divisionData) {
            uasort($divisionData, fn(ChallengeDivisionTeam $a, ChallengeDivisionTeam $b) => $this->scores[$b->getId()] - $this->scores[$a->getId()]);
        }

        foreach ($this->teamsByScores as $divId => $teamsByScore) {
            ksort($teamsByScore, SORT_NUMERIC);

            $this->teamsByScores[$divId] = array_reverse($teamsByScore, true);
        }

    }

    /**
     * @param ChallengeDivisionTeam $team
     * @param int $divisionId
     */
    protected function addTeam($team, $divisionId): void
    {
        $teamId = $team->getId();
        $this->sortedDivisionTeams[$divisionId][$teamId] = $team;
        $score = $this->getScore($team);
        $this->scores[$teamId] = $score;

        if (empty($this->teamsByScores[$divisionId][$score])) {
            $this->teamsByScores[$divisionId][$score] = [];
        }

        $this->teamsByScores[$divisionId][$score][$teamId] = $team;
    }
}
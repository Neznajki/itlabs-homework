<?php
declare(strict_types=1);


namespace App\Service;


use App\Entity\Challenge;
use App\Entity\ChallengeDivision;
use App\Entity\ChallengeDivisionTeam;
use App\Entity\DivisionMatch;
use App\Helper\MatchHelper;
use App\Repository\ChallengeDivisionTeamRepository;
use App\Repository\DivisionMatchRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\Exception\InvalidArgumentException;

class DivisionMatchService
{
    /** @var DivisionMatchRepository */
    private $divisionMatchRepository;
    /** @var ChallengeDivisionTeamRepository */
    private $challengeDivisionTeamRepository;

    /**
     * DivisionMatchService constructor.
     * @param DivisionMatchRepository $divisionMatchRepository
     * @param ChallengeDivisionTeamRepository $challengeDivisionTeamRepository
     */
    public function __construct(
        DivisionMatchRepository $divisionMatchRepository,
        ChallengeDivisionTeamRepository $challengeDivisionTeamRepository
    ) {
        $this->divisionMatchRepository = $divisionMatchRepository;
        $this->challengeDivisionTeamRepository = $challengeDivisionTeamRepository;
    }

    /**
     * @param int $matchId
     * @return DivisionMatch
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function calculateMatch(int $matchId): DivisionMatch
    {
        $match = $this->divisionMatchRepository->find($matchId);

        if ($match == null) {
            throw new InvalidArgumentException("division match {$matchId} not found");
        }

        if ($match->getTeamAWin() !== null) {
            throw new \InvalidArgumentException("division match {$matchId} already have results");
        }

        $match->setTeamAWin(
            MatchHelper::predictTeamAWin(
                $match->getTeamA(),
                $match->getTeamB()
            )
        );

        $match->setResulted(new DateTime());

        $this->divisionMatchRepository->save($match);

        return $match;
    }

    /**
     * @param ChallengeDivision $challengeDivision
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createMatches(ChallengeDivision $challengeDivision)
    {
        $challengeTeams = $this->challengeDivisionTeamRepository->getTeamsByChallengeDivision($challengeDivision);

        $createdMatches = [];
        foreach ($challengeTeams as $teamA) {
            foreach ($challengeTeams as $teamB) {
                if ($teamA->getId() == $teamB->getId()) {
                    continue;
                }

                $reverseIndex = "{$teamB->getId()}_{$teamA->getId()}";
                if (array_key_exists($reverseIndex, $createdMatches)) {
                    continue;
                }

                $index = "{$teamA->getId()}_{$teamB->getId()}";
                $divisionMatch = $this->createDivisionMatch($teamA, $teamB);

                $createdMatches[$index] = $divisionMatch;
            }
        }
    }

    /**
     * @param ChallengeDivision $challengeDivision
     * @return DivisionMatch[]
     */
    public function getDivisionMatches(ChallengeDivision $challengeDivision): array
    {
        return $this->divisionMatchRepository->getMatchesByChallengeDivision($challengeDivision);
    }

    /**
     * @param Challenge $challengeDivision
     * @return DivisionMatch[]
     */
    public function getChallengeMatches(Challenge $challengeDivision): array
    {
        return $this->divisionMatchRepository->getMatchesByChallenge($challengeDivision);
    }

    /**
     * @param ChallengeDivisionTeam $teamA
     * @param ChallengeDivisionTeam $teamB
     * @return DivisionMatch
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function createDivisionMatch(ChallengeDivisionTeam $teamA, ChallengeDivisionTeam $teamB): DivisionMatch
    {
        $divisionMatch = new DivisionMatch();

        $divisionMatch->setTeamA($teamA);
        $divisionMatch->setTeamB($teamB);
        $divisionMatch->setCreated(new DateTime());

        $this->divisionMatchRepository->save($divisionMatch);

        return $divisionMatch;
    }
}
<?php
declare(strict_types=1);


namespace App\Service;


use App\Entity\ChallengeDivision;
use App\Entity\DivisionMatch;
use App\Repository\ChallengeDivisionTeamRepository;
use App\Repository\DivisionMatchRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

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
                $index = "{$teamA->getId()}_{$teamB->getId()}";
                $reverseIndex = "{$teamB->getId()}_{$teamA->getId()}";

                if (array_key_exists($reverseIndex, $createdMatches)) {
                    continue;
                }

                $divisionMatch = new DivisionMatch();

                $divisionMatch->setTeamA($teamA);
                $divisionMatch->setTeamB($teamB);
                $divisionMatch->setCreated(new DateTime());

                $this->divisionMatchRepository->save($divisionMatch);

                $createdMatches[$index] = $divisionMatch;
            }
        }
    }
}
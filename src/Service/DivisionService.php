<?php
declare(strict_types=1);


namespace App\Service;


use App\Entity\Challenge;
use App\Entity\ChallengeDivision;
use App\Entity\ChallengeDivisionTeam;
use App\Entity\DivisionMatch;
use App\Entity\Team;
use App\Repository\ChallengeDivisionRepository;
use App\Repository\ChallengeDivisionTeamRepository;
use App\Repository\DivisionRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DivisionService
{
    /** @var ChallengeDivisionRepository */
    private $challengeDivisionRepository;
    /** @var ChallengeDivisionTeamRepository */
    private $challengeDivisionTeamRepository;
    /** @var DivisionRepository */
    private $divisionRepository;
    /** @var DivisionMatchService */
    private $divisionMatchService;

    /**
     * DivisionService constructor.
     * @param DivisionRepository $divisionRepository
     * @param ChallengeDivisionRepository $challengeDivisionRepository
     * @param ChallengeDivisionTeamRepository $challengeDivisionTeamRepository
     * @param DivisionMatchService $divisionMatchService
     */
    public function __construct(
        DivisionRepository $divisionRepository,
        ChallengeDivisionRepository $challengeDivisionRepository,
        ChallengeDivisionTeamRepository $challengeDivisionTeamRepository,
        DivisionMatchService $divisionMatchService
    ) {
        $this->divisionRepository = $divisionRepository;
        $this->challengeDivisionRepository = $challengeDivisionRepository;
        $this->challengeDivisionTeamRepository = $challengeDivisionTeamRepository;
        $this->divisionMatchService = $divisionMatchService;
    }

    /**
     * @param Challenge $challenge
     * @return ChallengeDivision[]
     */
    public function getDivisionsByChallenge(Challenge $challenge): array
    {
        return $this->challengeDivisionRepository->getByChallenge($challenge);
    }

    /**
     * @param Challenge $challenge
     * @return DivisionMatch[]
     */
    public function getDivisionMatchesByChallenge(Challenge $challenge): array
    {
        return $this->divisionMatchService->getChallengeMatches($challenge);
    }

    /**
     * @param ChallengeDivision $challengeDivision
     * @return DivisionMatch[]
     */
    public function getDivisionMatchesByChallengeDivision(ChallengeDivision $challengeDivision): array
    {
        return $this->divisionMatchService->getDivisionMatches($challengeDivision);
    }

    /**
     * @param ChallengeDivision $challengeDivision
     * @return ChallengeDivisionTeam[]
     */
    public function getTeamsByChallengeDivision(ChallengeDivision $challengeDivision): array
    {
        return $this->challengeDivisionTeamRepository->getTeamsByChallengeDivision($challengeDivision);
    }


    /**
     * @param Challenge $challenge
     * @param Team[] $teams
     * @return ChallengeDivision[]
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createChallengeDivisionsWithTeamsAndMatches(Challenge $challenge, array $teams): array
    {
        $allChDiv = $this->createChallengeDivisions($challenge);

        $this->createDivisionTeams($teams, $allChDiv);

        foreach ($allChDiv as $chDiv) {
            $this->divisionMatchService->createMatches($chDiv);
        }

        return $allChDiv;
    }

    /**
     * @param Challenge $challenge
     * @return ChallengeDivision[]
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function createChallengeDivisions(Challenge $challenge): array
    {
        $allDivisions = $this->divisionRepository->findAll();
        $allChallengeDivisions = [];

        foreach ($allDivisions as $division) {
            $challengeDivision = new ChallengeDivision();

            $challengeDivision->setChallenge($challenge);
            $challengeDivision->setDivision($division);

            $this->challengeDivisionRepository->save($challengeDivision);
            $allChallengeDivisions[] = $challengeDivision;
        }

        return $allChallengeDivisions;
    }

    /**
     * @param array $teams
     * @param array $allChDiv
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function createDivisionTeams(array $teams, array $allChDiv): void
    {
        $chDiv = [];
        foreach ($teams as $team) {
            if (empty($chDiv)) {
                $chDiv = $allChDiv;
            }

            $chDivTeam = new ChallengeDivisionTeam();

            $chDivTeam->setChallengeDivision(array_pop($chDiv));
            $chDivTeam->setTeam($team);
            $chDivTeam->setAssigned(new DateTime());

            $this->challengeDivisionTeamRepository->save($chDivTeam);
        }
    }
}
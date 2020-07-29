<?php
declare(strict_types=1);


namespace App\Service;


use App\DataObject\ChallengeData;
use App\Entity\Challenge;
use App\Repository\ChallengeRepository;
use App\Repository\PlayOfStepsRepository;
use App\Repository\TeamRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\Exception\InvalidArgumentException;

class ChallengeService
{
    /** @var ChallengeRepository */
    private $challengeRepository;
    /** @var TeamRepository */
    private $teamRepository;
    /** @var PlayOfStepsRepository */
    private $playOfStepsRepository;
    /** @var DivisionService */
    private $divisionService;
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * ChallengeService constructor.
     * @param ChallengeRepository $challengeRepository
     * @param TeamRepository $teamRepository
     * @param PlayOfStepsRepository $playOfStepsRepository
     * @param DivisionService $divisionService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ChallengeRepository $challengeRepository,
        TeamRepository $teamRepository,
        PlayOfStepsRepository $playOfStepsRepository,
        DivisionService $divisionService,
        EntityManagerInterface $entityManager
    ) {
        $this->challengeRepository = $challengeRepository;
        $this->teamRepository = $teamRepository;
        $this->playOfStepsRepository = $playOfStepsRepository;
        $this->divisionService = $divisionService;
        $this->entityManager = $entityManager;
    }

    public function getExistingChallengeData(int $challengeId): ChallengeData
    {
        $challenge = $this->challengeRepository->find($challengeId);

        if ($challenge == null) {
            throw new InvalidArgumentException("challenge with id {$challengeId} not found");
        }

        $divisions = $this->divisionService->getDivisionsByChallenge($challenge);

        $result = new ChallengeData();

        foreach ($divisions as $division) {
            $result->setDivisionTeams(
                $division,
                $this->divisionService->getTeamsByChallengeDivision($division)
            );

            $result->setDivisionMatches($division, $this->divisionService->getDivisionMatchesByChallengeDivision($division));
        }

        return $result;
    }

    /**
     * @param int $playOfId
     * @param array $teamsId
     * @return Challenge
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function startChallenge(int $playOfId, array $teamsId): Challenge
    {
        $playOf = $this->playOfStepsRepository->find($playOfId);

        if ($playOf == null) {
            throw new InvalidArgumentException("play of with id {$playOfId} not found");
        }

        $teams = $this->teamRepository->findBy(['id' => $teamsId]);

        if ($playOf->getMatchCount() * 4 > count($teams)) {
            throw new InvalidArgumentException(
                "play of teams count should have at least 4x times more teams to make tournament complete"
            );
        }

        $this->entityManager->beginTransaction();
        $challenge = new Challenge();

        $challenge->setCreated(new DateTime());
        $challenge->setName(sprintf('challenge %s', $challenge->getCreated()->format('Y-m-d H:i:s')));
        $this->challengeRepository->save($challenge);

        $this->divisionService->createChallengeDivisionsWithTeamsAndMatches(
            $challenge,
            $teams
        );
        $this->entityManager->commit();

        return $challenge;
    }
}
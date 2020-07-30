<?php
declare(strict_types=1);


namespace App\Service;


use App\DataObject\ChallengeDivisionChallenge;
use App\DataObject\ChallengePlayOfData;
use App\Entity\Challenge;
use App\Repository\ChallengeRepository;
use App\Repository\TeamRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;
use Symfony\Component\Form\Exception\InvalidArgumentException;

class ChallengeService
{
    /** @var ChallengeRepository */
    private ChallengeRepository $challengeRepository;
    /** @var TeamRepository */
    private TeamRepository $teamRepository;
    /** @var DivisionService */
    private DivisionService $divisionService;
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;
    /** @var PlayOfService */
    private PlayOfService $playOfService;

    /**
     * ChallengeService constructor.
     * @param ChallengeRepository $challengeRepository
     * @param TeamRepository $teamRepository
     * @param DivisionService $divisionService
     * @param EntityManagerInterface $entityManager
     * @param PlayOfService $playOfService
     */
    public function __construct(
        ChallengeRepository $challengeRepository,
        TeamRepository $teamRepository,
        DivisionService $divisionService,
        EntityManagerInterface $entityManager,
        PlayOfService $playOfService
    ) {
        $this->challengeRepository = $challengeRepository;
        $this->teamRepository = $teamRepository;
        $this->divisionService = $divisionService;
        $this->entityManager = $entityManager;
        $this->playOfService = $playOfService;
    }

    /**
     * @param int $challengeId
     * @return ChallengeDivisionChallenge
     */
    public function getExistingChallengeDivisionCollection(int $challengeId): ChallengeDivisionChallenge
    {
        $challenge = $this->getChallenge($challengeId);

        $divisions = $this->divisionService->getDivisionsByChallenge($challenge);

        $result = new ChallengeDivisionChallenge();

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
     * @param int $challengeId
     * @return ChallengePlayOfData
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getPlayOfData(int $challengeId): ChallengePlayOfData
    {
        $challenge = $this->getChallenge($challengeId);

        $matches = $this->playOfService->getPlayOffDataByChallenge($challenge);
        $challengeFirstStep = $this->playOfService->getFirstStepByChallenge($challenge);

        if (empty($matches)) {
            $matches = $this->playOfService->createPlayOffMatches(
                $challengeFirstStep,
                $this->divisionService->getDivisionMatchesByChallenge($challenge)
            );

            if (empty($matches)) {
                throw new RuntimeException("something got wrong no matches for {$challengeId}");
            }
        }

        return new ChallengePlayOfData($challenge, $this->playOfService->getPlayOfStepsByFirstStep($challengeFirstStep), $matches);
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
        $playOf = $this->playOfService->getStepById($playOfId);

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

        $this->playOfService->createChallengeFirstStep($challenge, $playOf);
        $this->divisionService->createChallengeDivisionsWithTeamsAndMatches(
            $challenge,
            $teams
        );

        $this->entityManager->commit();

        return $challenge;
    }

    /**
     * @param int $challengeId
     * @return Challenge
     */
    protected function getChallenge(int $challengeId): Challenge
    {
        $challenge = $this->challengeRepository->find($challengeId);

        if ($challenge == null) {
            throw new InvalidArgumentException("challenge with id {$challengeId} not found");
        }

        return $challenge;
    }
}
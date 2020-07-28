<?php
declare(strict_types=1);


namespace App\Service;


use App\DataObject\ChallengeData;
use App\Entity\Challenge;
use App\Repository\ChallengeRepository;
use App\Repository\PlayOfStepsRepository;
use App\Repository\TeamRepository;
use DateTime;
use Symfony\Component\Form\Exception\InvalidArgumentException;

class ChallengeService
{
    /** @var ChallengeRepository */
    private $challengeRepository;
    /** @var TeamRepository */
    private $teamRepository;
    /** @var PlayOfStepsRepository */
    private $playOfStepsRepository;

    /**
     * ChallengeService constructor.
     * @param ChallengeRepository $challengeRepository
     * @param TeamRepository $teamRepository
     * @param PlayOfStepsRepository $playOfStepsRepository
     */
    public function __construct(ChallengeRepository $challengeRepository, TeamRepository $teamRepository, PlayOfStepsRepository $playOfStepsRepository)
    {
        $this->challengeRepository = $challengeRepository;
        $this->teamRepository = $teamRepository;
        $this->playOfStepsRepository = $playOfStepsRepository;
    }

    public function getExistingChallengeData(int $challengeId): ChallengeData
    {
        return new ChallengeData();
    }

    public function startChallenge(int $playOfId, array $teamsId): Challenge
    {
        $playOf = $this->playOfStepsRepository->find($playOfId);

        if ($playOf == null) {
            throw new InvalidArgumentException("play of with id {$playOfId} not found");
        }

        $teams = $this->teamRepository->findBy(['id' => $teamsId]);

        if ($playOf->getMatchCount() * 4 < count($teams)) {
            throw new InvalidArgumentException("play of teams count should have at least 4x times more teams to make tournament complete");
        }

        $challenge = new Challenge();

        $challenge->setCreated(new DateTime());
        $challenge->setName(sprintf('challenge_%s', $challenge->getCreated()->format('Y-m-d H:i:s')));
        $this->challengeRepository->save($challenge);

        return $challenge;
    }
}
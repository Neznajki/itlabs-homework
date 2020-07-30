<?php
declare(strict_types=1);


namespace App\Service;


use App\Entity\Challenge;
use App\Entity\ChallengePlayOfStep;
use App\Entity\DivisionMatch;
use App\Entity\PlayOfMatch;
use App\Entity\PlayOfSteps;
use App\Factory\PlayOfFactory;
use App\Repository\ChallengePlayOfStepRepository;
use App\Repository\PlayOfMatchRepository;
use App\Repository\PlayOfStepsRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\Exception\InvalidArgumentException;

class PlayOfService
{
    /** @var PlayOfMatchRepository */
    private PlayOfMatchRepository $playOfMatchRepository;
    /** @var PlayOfStepsRepository */
    private PlayOfStepsRepository $playOfStepsRepository;
    /** @var ChallengePlayOfStepRepository */
    private ChallengePlayOfStepRepository $challengePlayOfStepRepository;

    /**
     * PlayOfService constructor.
     * @param PlayOfMatchRepository $playOfMatchRepository
     * @param PlayOfStepsRepository $playOfStepsRepository
     * @param ChallengePlayOfStepRepository $challengePlayOfStepRepository
     */
    public function __construct(
        PlayOfMatchRepository $playOfMatchRepository,
        PlayOfStepsRepository $playOfStepsRepository,
        ChallengePlayOfStepRepository $challengePlayOfStepRepository
    ) {
        $this->playOfMatchRepository = $playOfMatchRepository;
        $this->playOfStepsRepository = $playOfStepsRepository;
        $this->challengePlayOfStepRepository = $challengePlayOfStepRepository;
    }

    /**
     * @param ChallengePlayOfStep $challengeFirstStep
     * @return PlayOfSteps[]
     */
    public function getPlayOfStepsByFirstStep(ChallengePlayOfStep $challengeFirstStep)
    {
        return $this->playOfStepsRepository->getAllStepsByFirstStep($challengeFirstStep->getPlayOfStep());
    }

    /**
     * @param Challenge $challenge
     * @return ChallengePlayOfStep
     */
    public function getFirstStepByChallenge(Challenge $challenge): ChallengePlayOfStep
    {
        return $this->challengePlayOfStepRepository->getByChallenge($challenge);
    }

    /**
     * @param Challenge $challenge
     * @return PlayOfMatch[]
     */
    public function getPlayOffDataByChallenge(Challenge $challenge): array
    {
        return $this->playOfMatchRepository->getPlayOfDataByChallenge($challenge);
    }

    /**
     * @param ChallengePlayOfStep $challengeFirstStep
     * @param DivisionMatch[] $divisionMatches
     * @return PlayOfMatch[]
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createPlayOffMatches(ChallengePlayOfStep $challengeFirstStep, array $divisionMatches): array
    {
        $playOfStep = PlayOfFactory::createPlayOfByDivisionMatches(
            $challengeFirstStep->getPlayOfStep(),
            $divisionMatches
        );

        $this->playOfMatchRepository->massSave($playOfStep);

        return $playOfStep;
    }

    /**
     * @param int $playOfId
     * @return PlayOfSteps
     */
    public function getStepById(int $playOfId): PlayOfSteps
    {
        $playOf = $this->playOfStepsRepository->find($playOfId);

        if ($playOf == null) {
            throw new InvalidArgumentException("play of with id {$playOfId} not found");
        }

        return $playOf;
    }

    public function createChallengeFirstStep(Challenge $challenge, PlayOfSteps $playOfStep)
    {
        $entity = new ChallengePlayOfStep();

        $entity->setChallenge($challenge);
        $entity->setPlayOfStep($playOfStep);

        $this->challengePlayOfStepRepository->save($entity);
    }
}
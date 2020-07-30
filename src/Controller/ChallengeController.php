<?php
declare(strict_types=1);


namespace App\Controller;


use App\Repository\PlayOfStepsRepository;
use App\Repository\TeamRepository;
use App\Service\ChallengeService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChallengeController extends AbstractController
{

    /**
     * @Route("/challenge/division/dispaly/{challengeId}", name="challenge_division_display")
     * @param ChallengeService $challengeService
     * @param int $challengeId
     * @return Response
     */
    public function displayDivisionAction(ChallengeService $challengeService, int $challengeId): Response
    {
        return $this->render(
            'challengeDivisionDisplay.html.twig',
            [
                'title' => 'challenge division display',
                'challengeData' => $challengeService->getExistingChallengeDivisionCollection($challengeId),
                'challengeId' => $challengeId,
            ]
        );
    }

    /**
     * @Route("/challenge/play/of/dispaly/{challengeId}", name="challenge_play_of_display")
     * @param ChallengeService $challengeService
     * @param int $challengeId
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function displayPlayOfAction(ChallengeService $challengeService, int $challengeId): Response
    {
        return $this->render('challengePlayOfDisplay.html.twig', [
            'title' => 'challenge play off display',
            'challengePlayOfData' => $challengeService->getPlayOfData($challengeId),
        ]);
    }

    /**
     * @Route("/challenge/create", name="challenge_create")
     * @param PlayOfStepsRepository $playOfStepsRepository
     * @param TeamRepository $teamRepository
     * @return Response
     */
    public function createNewAction(
        PlayOfStepsRepository $playOfStepsRepository,
        TeamRepository $teamRepository
    ): Response {
        return $this->render(
            'challengeCreate.html.twig',
            [
                'title' => 'challenge create',
                'playOfs' => $playOfStepsRepository->findAll(),
                'teams' => $teamRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/challenge/start/{playOfId}", name="challenge_start")
     * @param ChallengeService $challengeService
     * @param int $playOfId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function startAction(ChallengeService $challengeService, int $playOfId): JsonResponse
    {
        if (empty($_REQUEST['teams'])) {
            throw new InvalidArgumentException('teams should be provided');
        }

        $challenge = $challengeService->startChallenge($playOfId, $_REQUEST['teams']);

        return new JsonResponse(['data' => $challenge]);
    }
}
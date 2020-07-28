<?php
declare(strict_types=1);


namespace App\Controller;


use App\Repository\PlayOfStepsRepository;
use App\Repository\TeamRepository;
use App\Service\ChallengeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChallengeController extends AbstractController
{

    /**
     * @Route("/challenge/dispaly/{challengeId}", name="challenge_display")
     * @param ChallengeService $challengeService
     * @param int $challengeId
     * @return Response
     */
    public function displayAction(ChallengeService $challengeService, int $challengeId): Response
    {
        return $this->render(
            'challengeDisplay.html.twig',
            [
                'title' => 'challenge display',
                'challengeData' => $challengeService->getExistingChallengeData($challengeId),
            ]
        );
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
}
<?php
declare(strict_types=1);


namespace App\Controller;


use App\Service\DivisionMatchService;
use App\Service\PlayOfService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MatchController
{
    /**
     * @Route("/division/match/calculate/{matchId}", name="division_match_calculate")
     * @param DivisionMatchService $divisionMatchService
     * @param int $matchId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function calculateDivisionAction(DivisionMatchService $divisionMatchService, int $matchId): JsonResponse
    {
        return new JsonResponse(['success' => 1, 'data' => $divisionMatchService->calculateMatch($matchId)]);
    }

    /**
     * @Route("/play/of/match/calculate/{matchId}", name="play_of_match_calculate")
     * @param PlayOfService $playOfService
     * @param int $matchId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function calculatePlayOfAction(PlayOfService $playOfService, int $matchId): JsonResponse
    {
        return new JsonResponse(['success' => 1, 'data' => $playOfService->calculateMatch($matchId)]);
    }
}
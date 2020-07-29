<?php
declare(strict_types=1);


namespace App\Controller;


use App\Service\DivisionMatchService;
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
    public function calculateAction(DivisionMatchService $divisionMatchService, int $matchId): JsonResponse
    {
        return new JsonResponse(['success' => 1, 'data' => $divisionMatchService->calculateMatch($matchId)]);
    }
}
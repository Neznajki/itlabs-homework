<?php


namespace App\Controller;


use App\Repository\TeamRepository;
use App\Service\TeamService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/team/list", name="team_list")
     * @param TeamRepository $teamRepository
     * @return Response
     */
    public function listAction(TeamRepository $teamRepository)
    {
        return $this->render('teamList.html.twig', ['title' => 'team list', 'list' => $teamRepository->findAll()]);
    }

    /**
     * @Route("/team/add/{name}/{strength}", name="team_add")
     * @param TeamService $teamService
     * @param string $name
     * @param int $strength
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function addAction(TeamService $teamService, string $name, int $strength): JsonResponse
    {
        try {
            return new JsonResponse($teamService->addTeam($name, $strength));
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (UniqueConstraintViolationException $exception) {
            return new JsonResponse(['message' => "team with name {$name} already exists"], 500);
        }
    }
}
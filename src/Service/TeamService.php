<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\Exception\InvalidArgumentException;

class TeamService
{
    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * TeamService constructor.
     * @param TeamRepository $teamRepository
     */
    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param string $name
     * @param int $strength
     * @return Team
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function addTeam(string $name, int $strength): Team
    {
        if (! $name) {
            throw new InvalidArgumentException('name is mandatory');
        }

        if (! $strength) {
            throw new InvalidArgumentException('strength is mandatory');
        }

        if ($strength < 1) {
            throw new InvalidArgumentException('strength should be more than 0');
        }

        $team = new Team();

        $team->setName($name);
        $team->setStrength($strength);

        $this->teamRepository->add($team);

        return $team;
    }
}
<?php
declare(strict_types=1);


namespace App\Service;


use App\Repository\ChallengeDivisionRepository;
use App\Repository\ChallengeDivisionTeamRepository;

class DivisionService
{

    /**
     * DivisionService constructor.
     */
    public function __construct(
        ChallengeDivisionRepository $challengeDivisionRepository,
        ChallengeDivisionTeamRepository $challengeDivisionTeamRepository
    )
    {
    }
}
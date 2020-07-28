<?php
declare(strict_types=1);


namespace App\Service;


use App\DataObject\ChallengeData;

class ChallengeService
{

    public function getExistingChallengeData(int $challengeId): ChallengeData
    {
        return new ChallengeData();
    }
}
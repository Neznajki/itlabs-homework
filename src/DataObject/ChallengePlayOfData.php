<?php
declare(strict_types=1);


namespace App\DataObject;


use App\Entity\Challenge;
use App\Entity\PlayOfMatch;
use App\Entity\PlayOfSteps;

class ChallengePlayOfData
{
    /** @var Challenge */
    private Challenge $challenge;
    /** @var PlayOfSteps[] */
    private array $steps;
    /** @var PlayOfMatch[] */
    private array $matches;

    /**
     * ChallengePlayOfData constructor.
     * @param Challenge $challenge
     * @param PlayOfSteps[] $steps
     * @param PlayOfMatch[] $matches
     */
    public function __construct(
        Challenge $challenge,
        array $steps,
        array $matches
    ) {
        $this->challenge = $challenge;
        $this->steps = $steps;
        $this->matches = $matches;
    }
}
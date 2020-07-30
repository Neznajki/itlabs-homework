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

    /**
     * @return PlayOfSteps[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * @param PlayOfSteps $step
     * @param int $matchNumber
     * @return PlayOfMatch|null
     */
    public function getStepMatch(PlayOfSteps $step, int $matchNumber): ?PlayOfMatch
    {
        $matchNumber --;

        foreach ($this->matches as $match) {
            if ($match->getPlayOfStep()->getId() == $step->getId()) {
                if ($matchNumber == 0) {
                    return $match;
                }

                $matchNumber--;
            }
        }

        return null;
    }
}
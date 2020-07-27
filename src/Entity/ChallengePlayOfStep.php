<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChallengePlayOfStep
 *
 * @ORM\Table(name="challenge_play_of_step", uniqueConstraints={@ORM\UniqueConstraint(name="challenge_play_of_step_challenge_id_uindex", columns={"challenge_id"})}, indexes={@ORM\Index(name="challenge_play_of_step_play_of_steps_id_fk", columns={"play_of_step_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ChallengePlayOfStepRepository")
 */
class ChallengePlayOfStep
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Challenge
     *
     * @ORM\ManyToOne(targetEntity="Challenge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     * })
     */
    private $challenge;

    /**
     * @var \PlayOfSteps
     *
     * @ORM\ManyToOne(targetEntity="PlayOfSteps")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="play_of_step_id", referencedColumnName="id")
     * })
     */
    private $playOfStep;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): self
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function getPlayOfStep(): ?PlayOfSteps
    {
        return $this->playOfStep;
    }

    public function setPlayOfStep(?PlayOfSteps $playOfStep): self
    {
        $this->playOfStep = $playOfStep;

        return $this;
    }


}

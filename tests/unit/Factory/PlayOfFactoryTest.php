<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\unit\Factory;

use App\Entity\ChallengeDivision;
use App\Entity\ChallengeDivisionTeam;
use App\Entity\DivisionMatch;
use App\Entity\PlayOfSteps;
use App\Entity\Team;
use App\Factory\PlayOfFactory;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Neznajka\Codeception\Engine\Abstraction\AbstractSimpleCodeceptionTest;

class PlayOfFactoryTest extends AbstractSimpleCodeceptionTest
{

    public function testCreatePlayOfByDivisionMatches()
    {
        $matches = [];

        $step = new PlayOfSteps();
        $step->setMatchCount(8);//4 per division

        $matches = array_merge($matches, $this->createNewDivision());
        $matches = array_merge($matches, $this->createNewDivision());

        $data = PlayOfFactory::createPlayOfByDivisionMatches($step, $matches);

        $tmp = [];
        $gg = [];

        foreach ($data as $tt) {
            $gg[] = [
                'winner' => $tt->getTeamA()->getTeam()->getName() . $tt->getTeamA()->getId(),
                'loser' => $tt->getTeamB()->getTeam()->getName() . $tt->getTeamB()->getId(),
            ];
        }

        foreach ($data as $playOfMatch) {
            $this->assertNotContains($playOfMatch->getTeamB()->getId(), $tmp);
            $this->assertNotContains($playOfMatch->getTeamA()->getId(), $tmp);

            $tmp[] = $playOfMatch->getTeamB()->getId();
            $tmp[] = $playOfMatch->getTeamA()->getId();

            $this->assertEquals('winner', $playOfMatch->getTeamA()->getTeam()->getName());
            $this->assertEquals('loser', $playOfMatch->getTeamB()->getTeam()->getName());
        }
    }

    protected function createDivisionMatch(ChallengeDivisionTeam $winner, ChallengeDivisionTeam $loser): DivisionMatch
    {
        $match = new DivisionMatch();

        $match->setTeamA($winner);
        $match->setTeamB($loser);

        $match->setTeamAWin(1);

        return $match;
    }


    protected function getWorkingClassName(): string
    {
        return PlayOfFactory::class;
    }

    /**
     * @param ChallengeDivision|MockObject $challengeOneMock
     * @param string $name
     * @return ChallengeDivisionTeam|MockObject
     * @throws Exception
     */
    protected function createTeamMock(ChallengeDivision $challengeOneMock, string $name = 'loser'): ChallengeDivisionTeam
    {
        $teamDivMock = $this->createMockExpectsOnlyMethodUsage(
            ChallengeDivisionTeam::class,
            ['getId', 'getChallengeDivision', 'getTeam']
        );

        $teamMock = $this->createMockExpectsOnlyMethodUsage(Team::class, ['getName']);

        $teamDivMock->expects($this->any())->method('getId')->willReturn($this->getInt());
        $teamDivMock->expects($this->any())->method('getTeam')->willReturn($teamMock);
        $teamMock->expects($this->any())->method('getName')->willReturn($name);
        $teamDivMock->expects($this->any())->method('getChallengeDivision')->willReturn($challengeOneMock);

        return $teamDivMock;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function createNewDivision(): array
    {
        $challengeOneMock = $this->createMockExpectsOnlyMethodUsage(ChallengeDivision::class, ['getId']);
        $challengeOneMock->expects($this->any())->method('getId')->willReturn($this->getInt());
        $result = [];

        $dominator = $this->createTeamMock($challengeOneMock, 'winner');
        $dominator2 = $this->createTeamMock($challengeOneMock, 'winner');
        $dominator3 = $this->createTeamMock($challengeOneMock, 'winner');
        $dominator4 = $this->createTeamMock($challengeOneMock, 'winner');
        $loser = $this->createTeamMock($challengeOneMock);
        $loser2 = $this->createTeamMock($challengeOneMock);
        $loser3 = $this->createTeamMock($challengeOneMock);
        $loser4 = $this->createTeamMock($challengeOneMock);
        $notIncluded = $this->createTeamMock($challengeOneMock, 'not included');

        $result[] = $this->createDivisionMatch($dominator, $loser);
        $result[] = $this->createDivisionMatch($dominator, $loser);
        $result[] = $this->createDivisionMatch($dominator, $loser3);
        $result[] = $this->createDivisionMatch($dominator2, $notIncluded);
        $result[] = $this->createDivisionMatch($dominator2, $loser);
        $result[] = $this->createDivisionMatch($dominator2, $loser);
        $result[] = $this->createDivisionMatch($dominator, $loser2);
        $result[] = $this->createDivisionMatch($dominator3, $notIncluded);
        $result[] = $this->createDivisionMatch($dominator3, $loser4);
        $result[] = $this->createDivisionMatch($dominator3, $loser4);
        $result[] = $this->createDivisionMatch($dominator4, $loser);
        $result[] = $this->createDivisionMatch($dominator4, $loser);
        $result[] = $this->createDivisionMatch($dominator4, $loser);
        $result[] = $this->createDivisionMatch($loser4, $loser);
        $result[] = $this->createDivisionMatch($loser4, $loser);
        $result[] = $this->createDivisionMatch($loser2, $loser);
        $result[] = $this->createDivisionMatch($loser2, $loser);
        $result[] = $this->createDivisionMatch($loser3, $loser);
        $result[] = $this->createDivisionMatch($loser3, $loser);
        $result[] = $this->createDivisionMatch($loser, $loser2);
        $result[] = $this->createDivisionMatch($loser, $loser2);
        $result[] = $this->createDivisionMatch($notIncluded, $loser2);

        return $result;
    }
}

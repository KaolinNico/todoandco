<?php

namespace App\Tests\Voter;

use App\Entity\Task;
use App\Entity\User;
use App\Voter\TaskVoter;
use LogicException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class TaskVoterTest extends TestCase
{
    protected static function getMethod($name)
    {
        $class = new ReflectionClass(TaskVoter::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testSupports()
    {
        $security = $this->createMock(Security::class);
        $taskVoter = new TaskVoter($security);
        $this->assertFalse(
            self::getMethod('supports')->invokeArgs(
                $taskVoter,
                [
                    TaskVoter::EDIT,
                    5
                ]
            )
        );
    }

    public function testVoteOnAttribute()
    {
        $security = $this->createMock(Security::class);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn('badUser');
        $taskVoter = new TaskVoter($security);
        $this->assertFalse(
            self::getMethod('voteOnAttribute')->invokeArgs(
                $taskVoter,
                [
                    TaskVoter::EDIT,
                    new Task(),
                    $token
                ]
            )
        );
    }

    public function testVoteOnAttributeException()
    {
        $security = $this->createMock(Security::class);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(new User());
        $taskVoter = new TaskVoter($security);
        $this->expectException(LogicException::class);
        self::getMethod('voteOnAttribute')->invokeArgs(
            $taskVoter,
            [
                'BADCONST',
                new Task(),
                $token
            ]
        );
    }
}
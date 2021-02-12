<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testId()
    {
        $this->assertEquals(null, $this->user->getId());
    }

    public function testUsername()
    {
        $this->user->setUsername('nico');
        $this->assertEquals('nico', $this->user->getUsername());
    }

    public function testPassword()
    {
        $this->user->setPassword('$2y$13$Aj9Ai6uykRGIG4oDGFdcP.E2OufpB6TBGb7DvP3NKgBSbWp.GHR32');
        $this->assertEquals('$2y$13$Aj9Ai6uykRGIG4oDGFdcP.E2OufpB6TBGb7DvP3NKgBSbWp.GHR32', $this->user->getPassword());
    }

    public function testEmail()
    {
        $this->user->setEmail('nico@nicocompany.com');
        $this->assertEquals('nico@nicocompany.com', $this->user->getEmail());
    }

    public function testAddTask()
    {
        $taskStub = $this->createMock(Task::class);
        $this->user->addTask($taskStub);
        $collection = $this->user->getTasks();
        $this->assertEquals(false, $collection->isEmpty());
    }

    public function testNoTasks()
    {
        $collection = $this->user->getTasks();
        $this->assertEquals(true, $collection->isEmpty());
    }

    public function testRoles()
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $this->user->getRoles());
    }

    public function testNoRoles()
    {
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
    }

    public function testSalt()
    {
        $this->assertEquals(null, $this->user->getSalt());
    }

    public function testEraseCredential()
    {
        static::assertSame(null, $this->user->eraseCredentials());
    }

    public function testRemoveTask()
    {
        $taskStub = new Task();
        $this->user->addTask($taskStub);
        $collection = $this->user->getTasks();
        $this->assertEquals(false, $collection->isEmpty());
        $this->user->removeTask($taskStub);
        $this->assertEquals(true, $collection->isEmpty());
    }
}
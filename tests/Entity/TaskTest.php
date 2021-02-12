<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $task;

    public function setUp()
    {
        $this->task = new Task();
    }

    public function testId()
    {
        $this->assertEquals(null, $this->task->getId());
    }

    public function testCreatedAt()
    {
        $this->task->setCreatedAt(new DateTime());
        $this->assertEquals(date('Y-m-d H:i:s'), $this->task->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testTitle()
    {
        $this->task->setTitle('Mon Super Titre');
        $this->assertEquals('Mon Super Titre', $this->task->getTitle());
    }

    public function testContent()
    {
        $this->task->setContent('Mon super contenu');
        $this->assertEquals('Mon super contenu', $this->task->getContent());
    }

    public function testIsDone()
    {
        $this->assertEquals(false, $this->task->IsDone());
        $this->task->setIsDone(true);
        $this->assertEquals(true, $this->task->getIsDone());
    }

    public function testToggle()
    {
        $this->assertEquals(false, $this->task->toggle(false));
    }

    public function testUser()
    {
        $userStub = $this->createMock(User::class);
        $this->task->setUser($userStub);
        $this->assertEquals($userStub, $this->task->getUser());
    }
}
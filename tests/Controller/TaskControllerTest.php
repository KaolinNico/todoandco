<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use LogTrait;

    /**
     * @var KernelBrowser
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testListAsAdmin()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertSame(1, $crawler->filter('a[href="/tasks/create"]')->count());
    }

    public function testListAsUser()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertSame(1, $crawler->filter('a[href="/tasks/create"]')->count());
    }

    public function testCreateTask()
    {
        $this->logInAdmin();
        $this->client->request('GET', '/tasks/create');
        $this->client->submitForm('Ajouter', [
            "task[title]" => "Nouvelle tâche de test",
            "task[content]" => "Ceci est un test de création de tâche !"
        ]);
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a été bien été ajoutée", $this->client->getResponse()->getContent());
    }

    public function testEditTask()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Nouvelle tâche de test");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->client->submitForm('Modifier', [
            "task[title]" => "Tâche de test modifiée",
            "task[content]" => "Ceci est un test de création de tâche !"
        ]);
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a bien été modifiée", $this->client->getResponse()->getContent());
    }

    public function testToggleTask()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche de test modifiée");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("a bien été marquée comme faite", $this->client->getResponse()->getContent());
    }

    public function testDeleteTaskAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche de test modifiée");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("pas autorisé à supprimer cette tâche", $this->client->getResponse()->getContent());
    }

    public function testDeleteTaskAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche de test modifiée");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a bien été supprimée", $this->client->getResponse()->getContent());
    }
}
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

    public function testCreateTaskAsAdmin()
    {
        $this->logInAdmin();
        $this->client->request('GET', '/tasks/create');
        $this->client->submitForm('Ajouter', [
            "task[title]" => "Nouvelle tâche administrateur",
            "task[content]" => "Ceci est un test de création de tâche !"
        ]);
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a été bien été ajoutée", $this->client->getResponse()->getContent());
    }

    public function testEditTaskAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Nouvelle tâche administrateur");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->client->submitForm('Modifier', [
            "task[title]" => "Nouvelle tâche administrateur modifiée",
            "task[content]" => "Ceci est un test de création de tâche !"
        ]);
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a bien été modifiée", $this->client->getResponse()->getContent());
    }

    public function testToggleTaskAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Nouvelle tâche administrateur modifiée");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("a bien été marquée comme faite", $this->client->getResponse()->getContent());
    }

    public function testDeleteTaskAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Nouvelle tâche administrateur modifiée");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a bien été supprimée", $this->client->getResponse()->getContent());
    }

    public function testCreateTaskAsUser()
    {
        $this->logInUser();
        $this->client->request('GET', '/tasks/create');
        $this->client->submitForm('Ajouter', [
            "task[title]" => "Nouvelle tâche utilisateur",
            "task[content]" => "Ceci est un test de création de tâche !"
        ]);
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a été bien été ajoutée", $this->client->getResponse()->getContent());
    }

    public function testEditTaskAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Nouvelle tâche utilisateur");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->client->submitForm('Modifier', [
            "task[title]" => "Nouvelle tâche utilisateur modifiée",
            "task[content]" => "Ceci est un test de création de tâche !"
        ]);
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a bien été modifiée", $this->client->getResponse()->getContent());
    }

    public function testToggleTaskAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Nouvelle tâche utilisateur modifiée");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("a bien été marquée comme faite", $this->client->getResponse()->getContent());
    }

    public function testDeleteTaskAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Nouvelle tâche utilisateur modifiée");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a bien été supprimée", $this->client->getResponse()->getContent());
    }

    public function testEditTaskAdminAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche administrateur");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleTaskAdminAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche administrateur");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskAdminAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche administrateur");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditTaskUserAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche utilisateur");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleTaskUserAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche utilisateur");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskUserAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche utilisateur");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditTaskAnonymousAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche anonyme");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleTaskAnonymousAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche anonyme");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskAnonymousAsUser()
    {
        $this->logInUser();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche anonyme");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditTaskAnonymousAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche anonyme");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleTaskAnonymousAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche anonyme");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskAnonymousAsAdmin()
    {
        $this->logInAdmin();
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("Tâche anonyme");
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a bien été supprimée", $this->client->getResponse()->getContent());
    }
}
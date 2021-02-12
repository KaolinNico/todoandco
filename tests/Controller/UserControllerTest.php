<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
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

    public function testListLogged()
    {
        $this->logInAdmin();
        $this->client->request('GET', '/users');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testListNotLogged()
    {
        $this->logInUser();
        $this->client->request('GET', '/users');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUser()
    {
        $this->logInAdmin();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername("new_user");
        $this->client->request('GET', '/users/' . $user->getId() . '/edit');
        $this->client->submitForm(
            'Modifier',
            [
                "user[username]" => "username",
                "user[plainPassword][first]" => "U!123password",
                "user[plainPassword][second]" => "U!123password",
            ]
        );
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("a bien été modifié", $this->client->getResponse()->getContent());
    }

    public function testDeleteUser()
    {
        $this->logInAdmin();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername("username");
        $this->client->request('GET', '/users/' . $user->getId() . '/delete');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("a bien été supprimé", $this->client->getResponse()->getContent());
    }

}
<?php

namespace App\Tests\Controller;

use App\Controller\SecurityController;
use App\Entity\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
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

    public function testRegistration()
    {
        $this->logInAdmin();
        $this->client->request('GET', '/users/create');
        $this->client->submitForm('Ajouter', [
            "user[username]" => "new_user",
            "user[plainPassword][first]" => "U!1password",
            "user[plainPassword][second]" => "U!1password",
            "user[email]" => "new_user@email.email",
            "user[roles]" => ["ROLE_USER"]
        ]);
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains("a bien été ajouté.", $this->client->getResponse()->getContent());

    }

    public function testLoginPage()
    {
        $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testLoginCheck()
    {
        $securityController = new SecurityController();
        $this->expectException(Exception::class);
        $securityController->loginCheck();
    }
    public function testLogoutCheck()
    {
        $securityController = new SecurityController();
        $this->expectException(Exception::class);
        $securityController->logoutCheck();
    }

}
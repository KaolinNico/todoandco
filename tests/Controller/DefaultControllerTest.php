<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    use LogTrait;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndexNotLogged()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertSame(2, $crawler->filter('form input#username')->count() + $crawler->filter('form input#password')->count());
    }

    public function testIndexLogged()
    {
        $this->logInUser();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Bienvenue sur Todo List', $this->client->getResponse()->getContent());
    }
}

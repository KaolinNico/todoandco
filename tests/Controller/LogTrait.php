<?php


namespace App\Tests\Controller;


use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait LogTrait
{

    private function logIn($user, $pass, $roles = ["ROLE_USER"])
    {
        $session = $this->client->getContainer()->get('session');
        $firewallName = 'main';
        $token = new UsernamePasswordToken($user, $pass, $firewallName, $roles);
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private function logInUser()
    {
        $this->logIn('toto', 'toto');
    }

    private function logInAdmin()
    {
        $this->logIn('nico', 'nico', ["ROLE_ADMIN"]);
    }
}
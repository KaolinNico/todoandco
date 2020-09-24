<?php


namespace App\Tests\Controller;


use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait LogTrait
{

    private function logInAdmin()
    {
        $session = $this->client->getContainer()->get('session');
        $firewallName = 'main';
        $token = new UsernamePasswordToken('nico', 'nico', $firewallName, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private function logInUser()
    {
        $session = $this->client->getContainer()->get('session');
        $firewallName = 'main';
        $token = new UsernamePasswordToken('toto', 'toto', $firewallName, array('ROLE_USER'));
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
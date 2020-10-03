<?php


namespace App\Tests\Controller;


use App\Repository\UserRepository;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait LogTrait
{

    private function logIn($username, $pass, $roles = ["ROLE_USER"])
    {
        $session = self::$container->get('session');
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername($username);

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
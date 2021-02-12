<?php


namespace App\Tests\Controller;


use App\Repository\UserRepository;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait LogTrait
{

    private function logInUser()
    {
        $this->logIn('demo_user');
    }

    private function logIn($username)
    {
        $session = self::$container->get('session');
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername($username);

        $firewallName = 'main';
        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_' . $firewallName, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private function logInAdmin()
    {
        $this->logIn('demo_admin');
    }
}
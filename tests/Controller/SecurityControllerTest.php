<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLoginPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button', 'Connection');
    }

    public function testSuccessLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('Connection');
        $form = $buttonCrawlerNode->form();
        $form['username'] = 'username 0';
        $form['password'] = 'test1234';
        $client->submit($form);

        $this->assertResponseRedirects('/', 302);
    }

    public function testFailLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('Connection');
        $form = $buttonCrawlerNode->form();
        $form['username'] = 'username';
        $form['password'] = 'test1234';
        $client->submit($form);

        $this->assertResponseRedirects('/login', 302);
    }

    public function testAlreadyLoggedLogin(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/login');
        $this->assertResponseRedirects('/', 302);
    }

    public function testLogout(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/logout');

        $this->assertResponseRedirects('', 302);
    }

}

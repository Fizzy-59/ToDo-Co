<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();
        $form['user[username]'] = 'username test';
        $form['user[password][first]'] = 'test1234';
        $form['user[password][second]'] = 'test1234';
        $form['user[email]'] = 'username@live.fr';
        $form['user[roles][0]']->select('ROLE_USER');
        $client->submit($form);

        $this->assertResponseIsSuccessful();
    }

    public function testEditUser(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/users/1/edit');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form();
        $form['user[username]'] = 'username test';
        $form['user[password][first]'] = 'test1234';
        $form['user[password][second]'] = 'test1234';
        $form['user[email]'] = 'username@live.fr';
        $form['user[roles][0]']->select('ROLE_USER');
        $form['user[roles][1]']->select('ROLE_ADMIN');
        $client->submit($form);

        $this->assertResponseIsSuccessful();
    }

    public function testDisplayListOfUsers(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testFailDisplayListOfUsers(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');
        $this->assertResponseRedirects('/login', 302);
    }
}

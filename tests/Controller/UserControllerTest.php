<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private static $userId;

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

        // Get the last user id
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findBy(array(),array('id'=>'DESC'),1,0);
        // Need to loop for recover id
        foreach($testUser as $user) { $id=$user->getId(); }
        self::$userId = (string) $id;

        $this->assertResponseRedirects('/users', 302);
    }

    public function testEditUser(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);

        $url = '/users/'.self::$userId.'/edit';
        $crawler = $client->request('GET', $url);

        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form();
        $form['user[username]'] = 'username modify test';
        $form['user[password][first]'] = 'test1234';
        $form['user[password][second]'] = 'test1234';
        $form['user[email]'] = 'username@live.fr';
        $form['user[roles][0]']->select('ROLE_ADMIN');
        $client->submit($form);

        $this->assertResponseRedirects('/users', 302);
    }

    public function testDeleteUSer(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);

        // Delete the test User
        $url = '/users/'.self::$userId.'/delete';
        $crawler = $client->request('GET', $url);

        $this->assertResponseRedirects('/', 302);
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

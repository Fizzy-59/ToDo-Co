<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testDisplayListOfTasks(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
    }

    public function testFailDisplayListOfTasks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');
        $this->assertResponseRedirects('/login', 302);
    }

    public function testAddTask(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tasks/create');

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();
        $form['task[title]'] = 'in testing title';
        $form['task[content]'] = 'in testing content';
        $client->submit($form);

        $this->assertResponseRedirects('/tasks', 302);
    }

    public function testDeleteTask(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tasks/26/delete');

        $this->assertResponseRedirects('/tasks', 302);
    }

    public function testDoneTask(): void
    {

    }
}

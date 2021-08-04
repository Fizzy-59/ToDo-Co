<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    static public $taskId;

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

        // Get the last task id
        $taskRepository = static::$container->get(TaskRepository::class);
        $testTask = $taskRepository->findBy(array(),array('id'=>'DESC'),1,0);
        // Need to loop for recover id
        foreach($testTask as $task) { $id=$task->getId(); }
        self::$taskId = (string) $id;
    }

    public function testEditTask(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);

        $url = '/tasks/'.self::$taskId.'/edit';
        $crawler = $client->request('GET', $url);

        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form();
        $form['task[title]'] = 'in testing title';
        $form['task[content]'] = 'in testing content';
        $client->submit($form);

        $this->assertResponseRedirects('/tasks', 302);
    }

    public function testDoneTask(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);

        $url = '/tasks/'.self::$taskId.'/toggle';
        $crawler = $client->request('GET', $url);

        $this->assertResponseRedirects('/tasks', 302);
    }

    public function testDeleteTask(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'username 0']);
        $client->loginUser($testUser);

        $url = '/tasks/'.self::$taskId.'/delete';
        $crawler = $client->request('GET', $url);

        $this->assertResponseRedirects('/tasks', 302);
    }

}

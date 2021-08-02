<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button', 'Connection');
    }

    public function testLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('Connection');
        $form = $buttonCrawlerNode->form();
        $form['username'] = 'username 0';
        $form['password'] = 'test1234';
        $client->submit($form);

         $this->assertResponseRedirects('/tasks', 302);
         $this->assertSelectorTextContains('button', 'Créer une tâche');
    }

    public function testLogout(): void
    {

    }
}

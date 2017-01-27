<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Marvel API', $crawler->filter('#container h1')->text());

        $this->assertEquals(6, $crawler->filter('a.list-group-item-heading')->count());
    }

    public function testCharacter()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'character/show/1009489');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Ben Parker', $crawler->filter('#container h1')->text());

        $this->assertEquals(3, $crawler->filter('.list-group-item')->count());


    }
}

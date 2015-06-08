<?php

namespace Blogger\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
	public function testAbout()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/about');

		$this->assertEquals(1, $crawler->filter('About Symblog')->count());
	}
}

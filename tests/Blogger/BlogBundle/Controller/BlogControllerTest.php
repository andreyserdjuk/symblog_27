<?php
namespace Tests\Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class BlogControllerTest extends WebTestCase
{
	public function testAbout()
	{
		$client = static::createClient();

		$crawler = $client->request(Request::METHOD_GET, '/about');

		$this->assertEquals(1, $crawler->filter('h1:contains("About symblog")')->count());
	}

	public function testAddComment()
	{
		$client = static::createClient();
		$crawler = $client->request(Request::METHOD_GET, '/');
		$href = $crawler->filter("section.main-col a")->first()->attr('href');
		$crawler = $client->request(Request::METHOD_GET, $href);

		$this->assertEquals(1, $crawler->filter('article.blog h2')->count());

		$form = $crawler->selectButton('Submit')->form();

		$crawler = $client->submit($form, array(
			'comment[user]'          => 'name',
			'comment[comment]'       => 'comment',
		));

		// Need to follow redirect
		$crawler = $client->followRedirect();

		// Check comment is now displaying on page, as the last entry. This ensure comments
		// are posted in order of oldest to newest
		$articleCrawler = $crawler->filter('section .previous-comments article')->last();

		$this->assertEquals('name', $articleCrawler->filter('header span.highlight')->text());
		$this->assertEquals('comment', $articleCrawler->filter('p')->last()->text());

		// Check the sidebar to ensure latest comments are display and there is 10 of them

		$this->assertEquals(10, $crawler->filter('aside.sidebar section')->last()
			->filter('article')->count()
		);

		$this->assertEquals('name', $crawler->filter('aside.sidebar section')->last()
			->filter('article')->first()
			->filter('header span.highlight')->text()
		);
	}

	/**
	 * @afterClass
	 */
	public static function tearDownAfterClass()
	{
		$client = static::createClient();
		$em = $client->getContainer()->get('doctrine.orm.entity_manager');
		$comment = $em->getRepository('BloggerBlogBundle:Comment')->findOneBy(['user' => 'name', 'comment' => 'comment']);
		if ($comment) {
			$em->remove($comment);
			$em->flush();
		}
	}
}

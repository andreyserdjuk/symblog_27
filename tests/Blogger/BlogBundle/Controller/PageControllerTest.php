<?php

namespace Tests\Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class PageControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$client = static::createClient();

		$crawler = $client->request(Request::METHOD_GET, '/');

		$this->assertTrue($crawler->filter('article.blog')->count() > 0);

		$blogLink   = $crawler->filter('article.blog h2 a')->first();
		$blogTitle  = $blogLink->text();
		$crawler    = $client->click($blogLink->link());

		// Check the h2 has the blog title in it
		$this->assertEquals(1, $crawler->filter('h2:contains("' . $blogTitle .'")')->count());
	}

	public function testContact()
	{
		$client = static::createClient();
		$client->enableProfiler();

		// log in
		$client->request(Request::METHOD_GET, '/contact');

		$this->assertTrue($client->getResponse()->isRedirect());
		$crawler = $client->followRedirect();
		$form = $crawler->selectButton('Log in')->form();
		$form['_username'] = 'fix';
		$form['_password'] = '12345';
		$client->submit($form);
		$this->assertTrue($client->getResponse()->isRedirect());
		$crawler = $client->followRedirect();

		// add contact
		//		$this->assertEquals(1, $crawler->filter('h1:contains("Contact symblog")')->count());
		$form = $crawler->selectButton('Submit')->form();
		$form['enquiry[name]']       = 'name';
		$form['enquiry[email]']      = 'email@email.com';
		$form['enquiry[subject]']    = 'Subject';
		$form['enquiry[body]']       = 'The comment body must be at least 50 characters long as there is a validation constrain on the Enquiry entity';

		$client->submit($form);

		// Check email has been sent
		if ($profile = $client->getProfile())
		{
			$swiftMailerProfiler = $profile->getCollector('swiftmailer');

			// Only 1 message should have been sent
			$this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

			// Get the first message
			$messages = $swiftMailerProfiler->getMessages();
			/** @var \Swift_Message $message */
			$message  = array_shift($messages);

			$this->assertInstanceOf('Swift_Message', $message);
			$this->assertEquals('Contact enquiry from symblog', $message->getSubject());
			$this->assertEquals('enquiries@symblog.co.uk', key($message->getFrom()));

			$symblogEmail = $client->getContainer()->getParameter('blogger_blog.emails.contact_email');
			// Check message is being sent to correct address
			$this->assertArrayHasKey($symblogEmail, $message->getTo());
		}

		$crawler = $client->followRedirect();

		$this->assertEquals(1, $crawler->filter('.flash-blogger-notice:contains("Your contact enquiry was successfully sent. Thank you!")')->count());
	}
}

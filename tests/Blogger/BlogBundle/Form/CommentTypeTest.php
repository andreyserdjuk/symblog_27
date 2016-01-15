<?php

namespace Tests\Blogger\BlogBundle\Form;

use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentTypeTest extends WebTestCase
{
    public function createSimilarComment()
    {
    }

    public function testSubmitValidData()
    {
        $client = WebTestCase::createClient();
        $container = $client->getContainer();

        $formData = [
            'user' => 'test user',
            'comment' => 'wish'
        ];

        $comment = new Comment();
        $comment->setUser($formData['user']);
        $comment->setComment($formData['comment']);

        $form = $container->get('form.factory')->create(CommentType::class, $comment);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($comment, $form->getData());
        $this->assertFalse($form->isValid());

        $formErrors = $form->getErrors(true);

        $this->assertEquals('Similar comment is already exists.', $formErrors->getChildren()->getMessage());
        $this->assertEquals(1, $formErrors->count());

        $view = $form->createView();
        $children = $view->children;

        foreach ($formData as $key => $value) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}

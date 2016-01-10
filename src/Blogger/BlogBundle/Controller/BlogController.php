<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Blog controller.
 */
class BlogController extends Controller
{
    /**
     * @Route(
     *      path="/{id}/{slug}",
     *      name="blogger_blog_show",
     *      requirements={"id"="\d+"}
     * )
     * @Template
     */
    public function showAction(Blog $blog)
    {
        return [
            'post'     => $blog,
            'comments' => $blog->getComments(),
        ];
    }
}
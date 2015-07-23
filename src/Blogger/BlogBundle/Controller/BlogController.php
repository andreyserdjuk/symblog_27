<?php
namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Blog controller.
 */
class BlogController extends Controller
{
    /**
     * @param Blog $blog
     * @param      $slug
     * @param      $comments
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Blog $blog, $slug, $comments)
    {
        return $this->render(
            'BloggerBlogBundle:Blog:show.html.twig',
            [
                'blog'     => $blog,
                'comments' => $blog->getComments(),
            ]
        );
    }
}
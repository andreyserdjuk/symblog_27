<?php
namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Blog controller.
 */
class BlogController extends Controller
{
    /**
     * @Route(
     *      path="/{id}/{slug}/{comments}",
     *      name="blogger_blog_show",
     *      requirements={"id"="\d+"},
     *      defaults={ _controller: "BloggerBlogBundle:Blog:show", comments: true }
     * )
     *
     * @param Blog $blog
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     */
    public function showAction(Blog $blog)
    {
        return [
            'blog'     => $blog,
            'comments' => $blog->getComments(),
        ];
    }
}
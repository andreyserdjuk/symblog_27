<?php
namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    /**
     * @param Blog $blog
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     */
    public function newAction(Blog $blog)
    {
        $comment = new Comment();
        $comment->setBlog($blog);

        $form = $this->createForm(
            CommentType::class,
            $comment,
            [
                'action' => $this->generateUrl(
                    'blogger_blog_comment_create',
                    [
                        'blog_id' => $blog->getId()
                    ]
                ),
                'method' => 'POST',
                'attr' => [
                    'class' => 'blogger',
                ]
            ]
        );

        return [
            'comment' => $comment,
            'form'    => $form->createView(),
        ];
    }

    /**
     * @Route(
     *      path="/comment/{blog_id}",
     *      name="blogger_blog_comment_create",
     *      requirements={"blog_id"="\d+"}
     * )
     * @ParamConverter("blog", class="BloggerBlogBundle:Blog", options={"mapping": {"blog_id": "id"}})
     * @Method({"POST"})
     */
    public function createAction(Request $request, Blog $blog)
    {
        $comment = new Comment();
        $comment->setBlog($blog);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();

            $slug = '#comment-' . $comment->getId();
        } else {
            $comments = $blog->getComments();
            $slug = $comments->isEmpty()? '' : '#comment' . $comments->first()->getId();
        }

        return $this->redirect(
            $this->generateUrl(
                'blogger_blog_show',
                [
                    'id'   => $comment->getBlog()->getId(),
                    'slug' => $comment->getBlog()->getSlug(),
                ]
            )
            . $slug
        );
    }
}

<?php
namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Form\CommentType;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends Controller
{
    /**
     * @param Blog $blog
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Blog $blog)
    {
        $comment = new Comment();
        $comment->setBlog($blog);
        $form = $this->createForm(new CommentType(), $comment);

        return $this->render(
            'BloggerBlogBundle:Comment:form.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView(),
        )
        );
    }

    /**
<<<<<<< HEAD
     * @param Blog $blog
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
=======
     * @Route(
     *      path="/comment/{blog_id}",
     *      name="blogger_blog_comment_create",
     *      requirements={"blog_id"="\d+"}
     * )
     * @Method({"POST"})
>>>>>>> develop
     */
    public function createAction(Blog $blog)
    {
        $comment = new Comment();
        $comment->setBlog($blog);

        $request = $this->container->get('request');

        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'blogger_blog_show',
                    [
                        'id'   => $comment->getBlog()->getId(),
                        'slug' => $comment->getBlog()->getSlug().'#comment'
                            .$comment->getId(),
                    ]
                )
                .'#comment-'.$comment->getId()
            );
        }

        return $this->render(
            'BloggerBlogBundle:Comment:create.html.twig',
            [
                'comment' => $comment,
                'form'    => $form->createView(),
            ]
        );
    }
}

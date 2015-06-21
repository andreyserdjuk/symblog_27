<?php
namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('BloggerBlogBundle:Blog');
        $blogs = $repository->getLatestBlogs();

        return $this->render("@BloggerBlog/Page/index.html.twig", ['blogs' => $blogs]);
    }

    public function aboutAction()
    {
        return $this->render("@BloggerBlog/about.html.twig");
    }

    public function contactAction()
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $request = $this->container->get('request_stack')->getCurrentRequest();
        if ($request->getMethod() == Request::METHOD_POST) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from symblog')
                    ->setFrom('enquiries@symblog.co.uk')
                    ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
                    ->setBody(
                        $this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));

                $this->get('mailer')->send($message);

                $this->addFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

                return $this->redirect($this->generateUrl('blogger_blog_contact'));
            }
        }

        return $this->render("@BloggerBlog/Page/contact.html.twig", ['form' => $form->createView()]);
    }

    public function sidebarAction()
    {
        $blogRepo = $this->getDoctrine()->getRepository('BloggerBlogBundle:Blog');
        $tags = $blogRepo->getTags();
        $tagWeights = $blogRepo->getTagWeights($tags);

        $commentLimit = $this->container->getParameter('blogger_blog.comments.latest_comment_limit');

        $latestComments = $this->getDoctrine()
            ->getRepository('BloggerBlogBundle:Comment')
            ->getLatestComments($commentLimit);

        return $this->render(
            'BloggerBlogBundle:Page:sidebar.html.twig',
            ['tags' => $tagWeights, 'latestComments' => $latestComments]
        );
    }
}
<?php
namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

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
        $aclProvider = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($blog);
        $acl = $aclProvider->createAcl($objectIdentity);

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclProvider->updateAcl($acl);

        return [
            'post'     => $blog,
            'comments' => $blog->getComments(),
        ];
    }
}
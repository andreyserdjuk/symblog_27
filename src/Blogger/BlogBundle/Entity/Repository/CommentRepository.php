<?php

namespace Blogger\BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function getCommentsForBlog($blogId, $approved = true)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.blog = :blog_id')
            ->addOrderBy('c.created')
            ->setParameter('blog_id', $blogId);

        if (false === is_null($approved)) {
            $qb->andWhere('c.approved = :approved')
                ->setParameter('approved', $approved);
        }

        return $qb
            ->getQuery()
            ->useResultCache(true)
            ->getResult()
        ;
    }


    public function getLatestComments($limit = 10)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->addOrderBy('c.updated', 'DESC');

        if (!is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb
            ->getQuery()
            ->useResultCache(false, 3600, 'latest_comments')
            ->getResult()
        ;
    }

    public function getSimilarByComment($value)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.comment LIKE :comment')
            ->setParameter('comment', '%' . $value . '%')
            ->getQuery()
            ->useResultCache(true)
            ->getResult();
    }
}

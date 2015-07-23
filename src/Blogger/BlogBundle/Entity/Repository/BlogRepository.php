<?php

namespace Blogger\BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class BlogRepository extends EntityRepository
{
    public function findAllOrderByCreatedDesc()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from('BloggerBlogBundle:Blog', 'b')
            ->orderBy('b.created', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getLatestBlogs($limit = 10)
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'c')
            ->leftJoin('b.comments', 'c')
            ->addOrderBy('b.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    public function getTags()
    {
        $blogTags = $this->createQueryBuilder('b')
            ->select('b.tags')
            ->getQuery()
            ->getResult();

        $tags = array();
        foreach ($blogTags as $blogTag)
        {
            $tags = array_merge(explode(",", $blogTag['tags']), $tags);
        }

        foreach ($tags as &$tag)
        {
            $tag = trim($tag);
        }

        return $tags;
    }

    public function getTagWeights($tags)
    {
        $tagWeights = array();
        if (empty($tags))
            return $tagWeights;

        foreach ($tags as $tag)
        {
            $tagWeights[$tag] = (isset($tagWeights[$tag])) ? $tagWeights[$tag] + 1 : 1;
        }
        // Shuffle the tags
        uksort($tagWeights, function() {
            return rand() > rand();
        });

        $max = max($tagWeights);

        // Max of 5 weights
        $multiplier = ($max > 5) ? 5 / $max : 1;
        foreach ($tagWeights as &$tag)
        {
            $tag = ceil($tag * $multiplier);
        }

        return $tagWeights;
    }
}
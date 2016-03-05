<?php

namespace Blogger\BlogBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * Class SimilarCommentConstraint
 * @package Blogger\BlogBundle\Validator\Constraints
 */
class SimilarCommentConstraint extends Constraint
{
    public $message = 'Similar comment is already exists.';

    /**
     * For current case it returns alias of service got from corresponding validator
     */
    public function validatedBy()
    {
        return 'commentUnique';
    }
}
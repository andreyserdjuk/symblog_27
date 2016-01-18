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

    public function validatedBy()
    {
        return 'commentUnique';
    }
}
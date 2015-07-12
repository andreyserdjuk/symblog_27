<?php
/**
 * Created by IntelliJ IDEA.
 * User: andrej
 * Date: 12.07.15
 * Time: 0:54
 */

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
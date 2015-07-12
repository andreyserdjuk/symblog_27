<?php
/**
 * Created by IntelliJ IDEA.
 * User: andrej
 * Date: 12.07.15
 * Time: 0:59
 */

namespace Blogger\BlogBundle\Validator\Constraints;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SimilarCommentConstraintValidator extends ConstraintValidator
{
    /** @var  ObjectManager */
    protected $em;

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        $similarComment = $this->em->getRepository("BloggerBlogBundle:Comment")
            ->getSimilarByComment($value);

        if ($similarComment) {
            /** ConstraintValidatorInterface $context */
            $context = $this->context;
            $context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    /**
     * @param ObjectManager $em
     */
    public function setEm(ObjectManager $em)
    {
        $this->em = $em;
    }
}
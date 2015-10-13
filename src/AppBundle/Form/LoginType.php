<?php
/**
 * Created by IntelliJ IDEA.
 * User: andrej
 * Date: 14.06.15
 * Time: 23:27
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', [
            'constraints' => [
                new Email()
            ]
        ]);
        $builder->add('password', 'password', [
            'constraints' => [
                new NotBlank()
            ]
        ]);
        $builder->add('submit', 'submit');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'login';
    }
}
<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'passportCode',
            TextType::class,
            [
                // 'position' is handled by https://github.com/egeloen/IvoryOrderedFormBundle
                'position' => 'first',
                'required' => false,
                'constraints' => [
                    new Length(['max' => 20]),
                ]
            ]
        );
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\FOSUserChild',
            'intention'  => 'registration',
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
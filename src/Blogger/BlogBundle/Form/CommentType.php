<?php

namespace Blogger\BlogBundle\Form;

use Blogger\BlogBundle\Validator\Constraints\SimilarCommentConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'user',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'You must enter your name',
                        ]),
                    ]
                ]
            )
            ->add(
                'comment',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'tinymce',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'You must enter a comment',
                        ]),
                        new SimilarCommentConstraint(),
                    ]
                ]
            )
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Blogger\BlogBundle\Entity\Comment',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'blogger_blogbundle_comment';
    }
}

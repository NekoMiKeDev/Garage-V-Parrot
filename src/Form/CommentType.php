<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'minlength' => '3',
                    'maxlength' => '30'
                ],
                'label' => 'Pseudonyme:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 3, 'max' => 50]),
                    new Assert\NotNull(),
                    new Assert\NotBlank()
                ]
            ])
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 w-100',
                    'minlength' => '10',
                    'maxlength' => '300'
                ],
                'label' => 'Commentaire:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 10, 'max' => 300]),
                    new Assert\NotNull(),
                    new Assert\NotBlank()
                ]
            ])
            ->add('rate', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 text-center w-100'
                ],
                'label' => 'Note:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'constraints' => [
                    new Assert\NotNull(),
                    new Assert\NotBlank(),
                    new Assert\Choice(['choices' => [1, 2, 3, 4, 5]])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-gold mt-4 mb-4 text-center'
                ],
                'label' => 'Envoyer un commentaire'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}

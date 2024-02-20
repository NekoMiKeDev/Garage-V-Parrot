<?php

namespace App\Form;

use App\Entity\Repair;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RepairType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-3 w-100',
                    'minlenght' => '2',
                    'maxlenght' => '180',
                ],
                'label' => 'Titre:',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 30]),
                ]
            ])
            ->add('repairType', ChoiceType::class, [
                'choices' => [
                    'Body Repair' => 'BODY REPAIR',
                    'Mechanical Repair' => 'MECHANICAL REPAIR',
                ],
                'attr' => [
                    'class' => 'form-control w-100',
                    'minlength' => '2',
                    'maxlength' => '180',
                ],
                'label' => 'Type de réparation',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])

            ->add('repairDescription', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-3 w-100',
                    'minlenght' => '2',
                    'maxlenght' => '180',
                ],
                'label' => 'Réparation description:',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 180]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-gold mt-5 mb-4 w-100'
                ],
                'label' => 'Créer une réparation'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repair::class,
        ]);
    }
}

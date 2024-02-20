<?php

namespace App\Form;

use App\Entity\Hours;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class HoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('openingHours', TextType::class, [
                'attr' => [
                    'class' => 'form-control text-center',
                    'maxlength' => '30'
                ],
                'label' => 'Matin-Ouverture:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 30]),
                ]
            ])
            ->add('closingHours', TextType::class, [
                'attr' => [
                    'class' => 'form-control text-center',
                    'maxlength' => '30'
                ],
                'label' => 'Après-midi-Ouverture:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 30]),
                ]
            ])

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-gold mt-4 mb-4'
                ],
                'label' => 'Changer les horraires'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hours::class,
        ]);
    }
}

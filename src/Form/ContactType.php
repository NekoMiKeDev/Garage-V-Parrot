<?php

namespace App\Form;

use App\Entity\Contact;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Objet',
                'label_attr' => [
                    'class' => 'contact-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'minlength' => '2',
                    'maxlength' => '30',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'contact-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 30]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'minlength' => '2',
                    'maxlength' => '30',
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'contact-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 30]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'minlength' => '5',
                    'maxlength' => '100'
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'contact-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 5, 'max' => 100]),
                    new Assert\Email(),
                    new Assert\NotBlank(),
                ],
            ])

            ->add('phoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'pattern' => '0[0-9]{9}',
                ],
                'label' => 'Téléphone',
                'label_attr' => [
                    'class' => 'contact-label',
                ],
                'constraints' => [
                    new Assert\NotNull(),
                    new Assert\Regex([
                        'pattern' => '/^0[0-9]{9}$/',
                        'message' => 'Numéro de téléphone français.',
                    ]),
                ],
            ])

            ->add('text', TextareaType::class, [
                'attr' => [
                    'minlength' => '20',
                    'maxlength' => '500',
                    'class' => 'text-input col-12 mb-3',
                ],
                'constraints' => [
                    new Assert\Length(['min' => 20, 'max' => 500]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-gold text-gold w-100'
                ],
                'label' => 'Envoyer'

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

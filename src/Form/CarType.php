<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Type;



class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('model', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'minlength' => '2',
                    'maxlength' => '30',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'Le modèle doit avoir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le modèle doit avoir au maximum {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'minlength' => '20',
                    'maxlength' => '200',
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 20,
                        'max' => 200,                        
                        'minMessage' => 'Le modèle doit avoir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le modèle doit avoir au maximum {{ limit }} caractères.'
                    ]),
                ]
            ])
            ->add('dateOfManufacture', DateType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
                'widget' => 'single_text',
                'label' => 'Année de mise en circulation: ',
                'label_attr' => [
                    'class' => 'form-label ',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])

            ->add('mileage', NumberType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
                'label' => 'Kilométrage: ',
                'label_attr' => [
                    'class' => 'form-label ',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le kilométrage doit être un nombre.',
                    ]),
            
                ],
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
                'label' => 'Prix: ',
                'label_attr' => [
                    'class' => 'form-label ',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Type(['type' => 'numeric', 'message' => 'Le Prix doit être un nombre.']),
                    new GreaterThan(['value' => 0, 'message' => 'Le Prix doit être supérieur à 0.']),
            
                ]
            ])

            ->add('inputFile', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple',

                ],
                'label' => 'Envoyer une/des images',
                'required' => false,
            ])

            ->add('pdfFile', FileType::class, [
                'mapped' => false,
                'multiple' => false,
                'attr' => [
                    'accept' => '.pdf',
                ],
                'label' => 'Envoyer un fichier PDF',
                'required' => false,
            ])

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-gold w-100'
                ],
                'label' => 'Envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}

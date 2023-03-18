<?php

namespace App\Form;

use App\Entity\TrickImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class TrickImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Nouvelle image',
                'help' => "Formats acceptés : jpg, jpeg, png, webp. Taille max : 1Mo",
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser {{ limit }} {{ suffix }}.',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Format de fichier invalide',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('currentImage', HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'readonly' => true
                ],
            ])
            ->add('trickId', HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'readonly' => true
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrickImage::class,
        ]);
    }
}

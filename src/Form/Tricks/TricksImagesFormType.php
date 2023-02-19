<?php

namespace App\Form\Tricks;

use App\Entity\TricksImages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksImagesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('image', FileType::class, [
            'attr' => [
                'class' => 'form-control',
                'multiple' => true,
                'accept' => 'image/png, image/jpeg',
            ],
            'label' => 'Image(s)'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TricksImages::class
        ]);
    }
}

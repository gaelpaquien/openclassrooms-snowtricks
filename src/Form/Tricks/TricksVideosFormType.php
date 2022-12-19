<?php

namespace App\Form\Tricks;

use App\Entity\TricksVideos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksVideosFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('video', FileType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Vidéo(s)'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TricksVideos::class,
        ]);
    }
}

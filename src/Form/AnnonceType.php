<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Tags;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('listPicture', FileType::class, [
                'label'=>"Photo",
                'data_class'=> null,
                'multiple'=>true
            ])
            ->add('description')
            ->add('prix')
            ->add('tags', EntityType::class, [
                'class'=>Tags::class,
                "choice_label"=>'nom',
                'multiple'=>true,
                'expanded'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}

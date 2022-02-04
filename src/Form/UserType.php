<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('role', ChoiceType::class ,[
                'choices' => [
                    'Utilisateur' => json_encode('ROLE_USER',JSON_UNESCAPED_UNICODE),
                    'Admin' => json_encode('ROLE_ADMIN', JSON_UNESCAPED_UNICODE)
                    ],
                'expanded'=>true,
                'multiple'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

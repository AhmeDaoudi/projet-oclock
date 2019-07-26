<?php

namespace App\Form\Api;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email', EmailType::class, [

            ])
            ->add('password', PasswordType::class, [
                    
                'empty_data' => '',
                'required' => true,
            ])
            ->add('firstname', TextType::class, [

                'empty_data' => '',
                'required' => false,
            ])
            ->add('lastname', TextType::class, [

                'empty_data' => '',
                'required' => false,
            ])
            ->add('pseudo', TextType::class, [

            ])
            ->add('photo', UrlType::class, [

                'empty_data' => 'https://www.loginradius.com/wp-content/plugins/all-in-one-seo-pack/images/default-user-image.png',
                'required' => false,
            ])
            ->add('birthdate', BirthdayType::class, [

                'empty_data' => '',
                'required' => false,
            ])
            ->add('biography', TextareaType::class, [

                'empty_data' => '',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

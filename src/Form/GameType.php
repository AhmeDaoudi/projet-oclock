<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Category;
use App\Entity\Developer;
use App\Entity\Editor;
use App\Entity\Platform;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, [
                
                'label' => 'Nom',
                'constraints' => [

                    new NotBlank(),
                    new NotNull(),
                ]
            ])
            ->add('description', TextareaType::class, [

                'label' => 'Description',
                'required' => false,
            ])
            ->add('cover', TextType::class, [

                'label' => 'Jaquette',
                'required' => false,
            ])
            ->add('illustration', TextType::class, [

                'label' => 'Image de fond',
                'required' => false,
            ])
            ->add('pegi', IntegerType::class, [

                'label' => 'PEGI',
                'required' => false,
            ])
            ->add('website', UrlType::class, [

                'label' => 'Site web',
                'required' => false,
            ])
            ->add('releasedAt', DateType::class, [

                'label' => 'Date de sortie',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('categories', EntityType::class, [

                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('developers', EntityType::class, [

                'class' => Developer::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('editors', EntityType::class, [

                'class' => Editor::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('platforms', EntityType::class, [

                'class' => Platform::class,
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}

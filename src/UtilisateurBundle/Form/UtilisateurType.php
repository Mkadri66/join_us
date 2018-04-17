<?php

namespace UtilisateurBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use VilleBundle\Entity\Ville;

use RoleBundle\Form\RoleType;
use VilleBundle\Form\VilleType;

class UtilisateurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder

            ->add('nom',        TextType::class)

            ->add('prenom',     TextType::class)

            ->add('mail',       TextType::class)

            ->add('pseudo',     TextType::class)

            ->add('mdp',        TextType::class)

            ->add('ville',      EntityType::class, array('label' => 'ville','class'=> Ville::class, 'choice_label'=> 'libelle'))

            ->add('avatar',     FileType::class)

            ->add('role',       RoleType::class)

            ->add('valider',    SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UtilisateurBundle\Entity\Utilisateur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'utilisateurbundle_utilisateur';
    }


}

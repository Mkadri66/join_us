<?php

namespace PartieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use VilleBundle\Entity\Ville;
use SportBundle\Entity\Sport;
use VilleBundle\Form\VilleType;
use UtilisateurBundle\Form\UtilisateurType;

class PartieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('adresse',        TextType::class)
        ->add('date',           DateTimeType::class)
        // ->add('termine',        ChoiceType::class)
        ->add('joueursActif',   IntegerType::class)
        ->add('totalJoueurs',   IntegerType::class)
        // ->add('organisateur',   UtilisateurType::class)
        ->add('sport',          EntityType::class, array('label' => 'sport','class'=> Sport::class, 'choice_label'=> 'libelle'))
        ->add('ville',          EntityType::class, array('label' => 'ville','class'=> Ville::class, 'choice_label'=> 'libelle'))
        ->add('valider',        SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PartieBundle\Entity\Partie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'partiebundle_partie';
    }


}

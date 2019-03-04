<?php

namespace PartyBundle\Form;

use CityBundle\Entity\City;
use SportBundle\Entity\Sport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PartyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('address',            TextType::class)
        ->add('date',               DateType::class, ['widget' => 'single_text']) 
        ->add('schedule',           TimeType::class, array('label'=> 'Horaire')) 
        ->add('totalPlayers',       IntegerType::class)
        ->add('sport',              EntityType::class, array('label' => 'sport','class'=> Sport::class, 'choice_label'=> 'name'))
        ->add('city',               EntityType::class, array('label' => 'ville','class'=> City::class, 'choice_label'=> 'name'))
        ->add('valider',            SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PartyBundle\Entity\Party'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'partybundle_party';
    }


}

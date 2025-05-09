<?php

namespace App\Form;

use App\Entity\Conges;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CongesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
        ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
            ])
            ->add('estValide')
            ->add('ref_utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conges::class,
        ]);
    }
}

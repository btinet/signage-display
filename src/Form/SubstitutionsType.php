<?php

namespace App\Form;

use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubstitutionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate',DateType::class,[
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'data' => new \DateTimeImmutable(),
                'required' => true
            ])
            ->add('endDate',DateType::class,[
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'data' => new \DateTimeImmutable(),
                'required' => true
            ])
            ->add('simulate',CheckboxType::class,[
                'required' => false,
                'label' => 'Import simulieren?',
                'data' => true
            ])
            ->add('delete_old_entries',CheckboxType::class,[
                'required' => false,
                'label' => 'vorherigen Import entfernen?',
                'data' => true
            ])
            ->add('use_black_list',CheckboxType::class,[
                'required' => false,
                'label' => 'Ausschlussliste beachten?',
                'data' => true
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'Daten holen',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

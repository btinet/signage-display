<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reload_interval', IntegerType::class, [
                'label' => 'Auto-Reload Intervall (in Sekunden)',
                'help' => 'Wie oft soll sich der Screen automatisch neu laden? (z.B. 1800 für 30 Min)',
            ])
            ->add('show_schedule_column', CheckboxType::class, [
                'label' => 'Vertretungsplan-Spalte (rechts) anzeigen',
                'required' => false,
            ]);
    }

}
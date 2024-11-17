<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)  // Définir le type explicite du champ title
            ->add('description', TextType::class, [
                'required' => false, // Rendre la description facultative
            ])
            ->add('isCompleted', CheckboxType::class, [
                'label' => 'Completed',
                'required' => false, // Pas obligatoire de cocher cette case
                'mapped' => true, // Assurer que la case à cocher est liée au champ 'isCompleted' de l'entité
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text', // Un champ de date au format texte
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}

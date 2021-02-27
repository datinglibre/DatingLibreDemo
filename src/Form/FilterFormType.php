<?php

declare(strict_types=1);

namespace App\Form;

use DatingLibre\AppBundle\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FilterFormType extends AbstractType
{
    private array $distances;
    private const INTERESTS = 'interests';
    private const REGIONS = 'regions';

    public function __construct()
    {
        $this->distances = [
            '100' => '100000',
            '75' => '75000',
            '50' => '50000',
            '25' => '25000'
        ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilterForm::class,
            self::REGIONS => [],
            self::INTERESTS => []
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('region', EntityType::class, [
            'choices' => $options[self::REGIONS],
            'class' => Region::class,
            'choice_label' => 'name',
            'placeholder' => '',
            'required' => false
        ]);

        $builder->add('distance', ChoiceType::class, [
            'choices' => $this->distances,
            'choice_label' => function ($choice) {
                return $choice/1000 . " km";
            },
            'label' => 'search.distance',
            'placeholder' => '',
            'required' => false
        ]);

        $builder->add('minAge', ChoiceType::class, [
            'choices' => range(18, 100),
            'label' => 'search.minimum_age',
            'choice_label' => function ($choice) {
                return $choice;
            },
            'required' => true
        ]);

        $builder->add('maxAge', ChoiceType::class, [
            'choices' => range(18, 100),
            'label' => 'search.maximum_age',
            'choice_label' => function ($choice) {
                return $choice;
            },
            'required' => true
        ]);

        $builder->add(
            'interests',
            ChoiceType::class,
            [
                'choices' => $options[self::INTERESTS],
                'choice_label' => 'name',
                'choice_translation_domain' => 'interests',
                'choice_value' => 'id',
                'multiple' => true,
                'expanded' => true
            ]
        );

        $builder->add('save', SubmitType::class, [
            'label' => 'search.search'
        ]);
    }
}

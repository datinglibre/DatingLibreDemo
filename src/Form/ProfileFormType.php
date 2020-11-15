<?php

declare(strict_types=1);

namespace App\Form;

use DatingLibre\AppBundle\Entity\Attribute;
use DatingLibre\AppBundle\Entity\Country;
use DatingLibre\AppBundle\Form\CountryFieldSubscriber;
use DatingLibre\AppBundle\Form\RegionFieldSubscriber;
use DatingLibre\AppBundle\Repository\CategoryRepository;
use DatingLibre\AppBundle\Repository\CountryRepository;
use DatingLibre\AppBundle\Repository\RegionRepository;
use DatingLibre\AppBundle\Validator\LettersAndNumbers;
use DatingLibre\AppBundle\Validator\UniqueUsername;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileFormType extends AbstractType
{
    private CategoryRepository $categoryRepository;
    private CountryRepository $countryRepository;
    private RegionRepository $regionRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        CountryRepository $countryRepository,
        RegionRepository $regionRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->countryRepository = $countryRepository;
        $this->regionRepository = $regionRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ProfileForm::class]);
    }

    public function buildForm(FormBuilderInterface $profileFormBuilder, array $options)
    {
        $countries = $this->countryRepository->findAll();
        $sex = $this->categoryRepository->findOneBy(['name' => 'sex']);
        $relationship = $this->categoryRepository->findOneBy(['name' => 'relationship']);

        $profileFormBuilder->add(
            'sex',
            EntityType::class,
            [
                    'label' => 'sex.profile',
                    'placeholder' => '',
                    'choices' => $sex->getAttributes(),
                    'class' => Attribute::class,
                    'choice_label' => 'name',
                    'choice_translation_domain' => 'attributes',
            ]
        );

        $profileFormBuilder->add(
            'relationship',
            EntityType::class,
            [
                'label' => 'relationship.profile',
                'placeholder' => '',
                'choices' => $relationship->getAttributes(),
                'class' => Attribute::class,
                'choice_label' => 'name',
                'choice_translation_domain' => 'attributes',
            ]
        );

        $profileFormBuilder->add(
            'username',
            TextType::class,
            [
                'label' => 'profile.username',
                'constraints' => [
                    new LettersAndNumbers([
                        'message' => 'profile.username_letters_and_numbers',
                    ]),
                    new UniqueUsername([
                        'message' => 'profile.unique_username'
                    ]),
                    new NotBlank([
                        'message' => 'profile.blank_username',
                    ]),
                    new Length([
                        'minMessage' => "profile.username_invalid_length",
                        'maxMessage' => "profile.username_invalid_length",
                        'min' => 3,
                        'max' => 32
                    ])]
            ]
        );

        $profileFormBuilder->add(
            'dob',
            DateType::class,
            [
                'years' => range(date('Y')-50, date('Y')-18),
                'label' => 'profile.dob'
            ]
        );

        $profileFormBuilder->add(
            'country',
            EntityType::class,
            [
                'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                'choices' => $countries,
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => '',
                'constraints' => [new NotBlank()]
            ]
        );


        $profileFormBuilder->addEventSubscriber(new CountryFieldSubscriber($this->countryRepository));
        $profileFormBuilder->addEventSubscriber(new RegionFieldSubscriber(
            $profileFormBuilder->getFormFactory(),
            $this->regionRepository
        ));

        $profileFormBuilder->add(
            'about',
            TextareaType::class,
            [
                'attr' => ['rows' => 5],
                'required' => false,
                'label' => 'profile.about',
                'constraints' => [
                    new Length([
                        'minMessage' => "profile.about_invalid_length",
                        'maxMessage' => "profile.about_invalid_length",
                        'min' => 0,
                        'max' => 6000
                    ])]
            ]
        );

        $profileFormBuilder->add('save', SubmitType::class, [
            'attr' => ['id' => 'save'],
            'label' => 'controls.save'
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Offer;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'label' => "Название оффера",
            ])
            ->add('isActive',CheckboxType::class, [
        'label' => 'Активность',
        'row_attr' => [
            'class' => 'from-group mb-1'
        ],
    ])
            ->add('logotype', FileType::class, [
                'label' => 'Логотип',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                'row_attr' => [
                    'class' => 'from-group mb-3',
                    'id' => 'logo'
                ],

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new Image([
                        'maxWidth' => 1000,
                        'maxHeight' => 1000,
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('maxLoanAmount', NumberType::class, [
                'label' => "Максимальная сумма займа",
                'row_attr' => [
                    'class' => 'from-group mb-1'
                ],
            ])
            ->add('interestRate', NumberType::class, [
                'label' => "Процент по займу",
                'row_attr' => [
                    'class' => 'from-group mb-1'
                ],
            ])
            ->add('minLoanPeriodDays', NumberType::class, [
                'label' => "Минимальный период займа в днях",
                'row_attr' => [
                    'class' => 'from-group mb-1'
                ],
            ])
            ->add('maxLoanPeriodDays', NumberType::class, [
                'label' => "Максимальный период займа в днях",
                'row_attr' => [
                    'class' => 'from-group mb-1'
                ],
            ])
            ->add('url', TextType::class, [
                'label' => "Реферальная ссылка",
                'row_attr' => [
                    'class' => 'from-group mb-1'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}

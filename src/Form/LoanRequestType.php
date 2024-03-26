<?php

namespace App\Form;

use App\Entity\LoanRequest;
use App\Entity\Offer;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class LoanRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input kirilik_validation', 'placeholder' => "Имя"],
                'label' => 'Имя'
            ])
            ->add('surname', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Фамилия"],
                'label' => 'Фамилия'
            ])
            ->add('patron', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Отчество"],
                'label' => 'Отчество'
            ])
            ->add('phone', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input phone_mask', 'placeholder' => "Телефон"],
                'label' => 'Телефон'
            ])
            ->add('code', TextType::class,  [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Код"],
                'label' => 'Код'
            ])
            ->add('birth', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input date_mask birth_validate', 'placeholder' => "01/12/1990 Дата рождения"],
                'label' => '01/12/1990 Дата рождения'
            ])
            ->add('email', EmailType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input email_mask', 'placeholder' => "Email"],
                'label' => 'Email'
            ])
            ->add('passportSeries', NumberType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Серия"],
                'label' => 'Серия'
            ])
            ->add('passportNumber', NumberType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Номер"],
                'label' => 'Номер'
            ])
            ->add('departmentCode', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input suggest_dep_code', 'placeholder' => "Код подразделения"],
                'label' => 'Код подразделения'
            ])
            ->add('issueDate', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input date_mask', 'placeholder' => "01/12/2000 Дата выдачи"],
                'label' => 'Дата выдачи'
            ])
            ->add('department', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Кем выдан"],
                'label' => 'Кем выдан'
            ])
            ->add('birthPlace', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group form-floating mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input suggest_city', 'placeholder' => "Место рождения"],
                'label' => 'Место рождения'
            ])
            ->add('regPlace', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3 form-floating'
                ],
                'attr' => [
                    'class' => 'form-control loan_form_input suggest_address',
                    'placeholder' => "Адрес регистрации"
                ],
                'label' => 'Адрес регистрации'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LoanRequest::class,
        ]);
    }
}

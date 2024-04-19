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
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input kirilik_validation', 'placeholder' => "Имя"],
            ])
            ->add('surname', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input', 'placeholder' => "Фамилия"],
            ])
            ->add('patron', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input', 'placeholder' => "Отчество"],
            ])
            ->add('birth', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input date_mask birth_validate', 'placeholder' => "дд.мм.гггг Дата рождения"],
            ])
            ->add('email', EmailType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input email_mask', 'placeholder' => "Email"],
            ])
            ->add('passportSeries', NumberType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input', 'placeholder' => "Серия"],
            ])
            ->add('passportNumber', NumberType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input', 'placeholder' => "Номер"],
            ])
            ->add('departmentCode', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input suggest_dep_code', 'placeholder' => "Код подразделения"],
            ])
            ->add('issueDate', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input date_mask', 'placeholder' => "дд.мм.гггг Дата выдачи"],
            ])
            ->add('department', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input', 'placeholder' => "Кем выдан"],
            ])
            ->add('birthPlace', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input suggest_city', 'placeholder' => "Место рождения"],
            ])
            ->add('regPlace', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => [
                    'class' => 'form-control-lg loan_form_input suggest_address',
                    'placeholder' => "Адрес регистрации"
                ],
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

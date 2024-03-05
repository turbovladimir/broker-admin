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
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Имя"],
                'label' => false
            ])
            ->add('surname', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Фамилия"],
                'label' => false
            ])
            ->add('patron', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Отчество"],
                'label' => false
            ])
            ->add('phone', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Телефон"],
                'label' => false
            ])
            ->add('birth', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Дата рождения"],
                'label' => false
            ])
            ->add('email', EmailType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Email"],
                'label' => false
            ])
            ->add('passportSeries', NumberType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Серия"],
                'label' => false
            ])
            ->add('passportNumber', NumberType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Номер"],
                'label' => false
            ])
            ->add('departmentCode', NumberType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Код подразделения"],
                'label' => false
            ])
            ->add('issueDate', DateType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Дата выдачи"],
                'label' => false
            ])
            ->add('region', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Регион"],
                'label' => false
            ])
            ->add('city', TextType::class,  [
                'row_attr' => [
                    'class' => 'from-group mb-3'
                ],
                'attr' => ['class' => 'form-control loan_form_input', 'placeholder' => "Город"],
                'label' => false
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

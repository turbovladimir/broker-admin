<?php

namespace App\Form;

use App\Entity\LoanRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'attr' => ['class' => 'form-control-lg loan_form_input email_mask', 'placeholder' => "Электронная почта"],
            ])
            ->add('birthPlace', TextType::class,  [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group form-group-lg mb-3'
                ],
                'attr' => ['class' => 'form-control-lg loan_form_input suggest_city', 'placeholder' => "Место рождения"],
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

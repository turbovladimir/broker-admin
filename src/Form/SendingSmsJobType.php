<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\SendingSmsJob;
use App\Entity\Sms;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendingSmsJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addedAt')
            ->add('sendingTime')
            ->add('status')
            ->add('errorText')
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
'choice_label' => 'id',
            ])
            ->add('sms', EntityType::class, [
                'class' => Sms::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SendingSmsJob::class,
        ]);
    }
}

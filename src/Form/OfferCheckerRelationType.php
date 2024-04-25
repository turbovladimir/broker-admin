<?php

namespace App\Form;

use App\Entity\Offer;
use App\Entity\OfferCheckerRelation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferCheckerRelationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('externalOfferId')
            ->add('checker', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'ЛидГид' => OfferCheckerRelation::CHECKER_LEAD_GID,
                ],
                'attr' => [
                    'class' => 'form-control mb-1'
                ],
                'row_attr' => [
                    'class' => 'from-group mb-1'
                ],

            ])
            ->add('offer', EntityType::class, [
                'class' => Offer::class,
                'choice_label' =>
                    fn(Offer $offer) => sprintf('ID: %d %s', $offer->getId(), $offer->getName()),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OfferCheckerRelation::class,
        ]);
    }
}

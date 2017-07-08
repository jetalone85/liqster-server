<?php

namespace Liqster\HomePageBundle\Form;

use Liqster\HomePageBundle\Entity\Account;
use Liqster\HomePageBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountPaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'product', EntityType::class, [
                    'class' => Product::class,
                    'data_class' => Product::class,
                    'required' => true,
                    'label' => 'Produkt',
                    'choice_label' => 'type',
                    'expanded' => true,
                    'attr' => [
                        'class' => 'input-field',
                    ]
                ]
            )
            ->add(
                'Dalej', SubmitType::class, [
                    'attr' => [
                        'class' => 'form-control btn waves-effect waves-light rigth']
                ]
            );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Account::class
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'liqster_homepagebundle_account';
    }
}

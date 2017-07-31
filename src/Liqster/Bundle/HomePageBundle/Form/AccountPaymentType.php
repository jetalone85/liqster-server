<?php

namespace Liqster\Bundle\HomePageBundle\Form;

use Liqster\Bundle\HomePageBundle\Entity\Account;
use Liqster\Bundle\HomePageBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                    'data_class' => null,
                    'required' => true,
                    'label' => '',
                    'choice_label' => 'type',
                    'expanded' => true,
                    'attr' => [
                        'class' => '',
                    ]
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

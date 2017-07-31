<?php

namespace Liqster\Bundle\HomePageBundle\Form;

use Liqster\Bundle\HomePageBundle\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountEditCommentsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name', HiddenType::class, [
                    'required' => true,
                    'label' => 'name',
                    'attr' => [
                        'class' => 'input'
                    ]
                ]
            )
            ->add(
                'password', HiddenType::class, [
                    'required' => true,
                    'label' => 'password',
                    'attr' => [
                        'class' => 'input'
                    ]
                ]
            )
            ->add('comments', TextareaType::class, [
                'required' => false
            ]);
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

<?php

namespace Liqster\HomePageBundle\Form;

use Liqster\HomePageBundle\Entity\Schedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountProgramType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('morning', ChoiceType::class, [
                'mapped' => true,
                'choices' => ['On' => true, 'Off' => false],
            ])
            ->add('noon', ChoiceType::class, [
                'mapped' => true,
                'choices' => ['On' => true, 'Off' => false],
            ])
            ->add('afternoon', ChoiceType::class, [
                'mapped' => true,
                'choices' => ['On' => true, 'Off' => false],
            ])
            ->add('evening', ChoiceType::class, [
                'mapped' => true,
                'choices' => ['On' => true, 'Off' => false],
            ])
            ->add('night', ChoiceType::class, [
                'mapped' => true,
                'choices' => ['On' => true, 'Off' => false],
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
                'data_class' => Schedule::class
            ]
        );
    }
}

<?php

namespace Liqster\HomePageBundle\Form;

use Liqster\HomePageBundle\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountEditTagsType extends AbstractType
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
                        'class' => 'form-control'
                    ]
                ]
            )
            ->add(
                'password', HiddenType::class, [
                    'required' => true,
                    'label' => 'password',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )
            ->add('tagsText', TextareaType::class, [
                'required' => false
            ])
            ->add(
                'Zapisz', SubmitType::class, [
                    'attr' => [
                        'class' => '']
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

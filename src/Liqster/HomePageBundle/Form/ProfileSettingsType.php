<?php

namespace Liqster\HomePageBundle\Form;

use Liqster\HomePageBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileSettingsType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'options' => ['translation_domain' => 'FOSUserBundle'],
                    'first_options' => ['label' => 'form.password'],
                    'second_options' => ['label' => 'form.password_confirmation'],
                    'invalid_message' => 'fos_user.password.mismatch',
                    'label' => 'Email',
                    'attr' => [
                        'class' => 'form-control'
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
                'data_class' => User::class
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

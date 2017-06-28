<?php

namespace Liqster\UserBundle\Form;

use FOS\UserBundle\Form\Type\UsernameFormType;
use Liqster\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationType
 * @package Liqster\UserBundle\Form
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle'
            ])
            ->add('username', UsernameFormType::class, [
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'translation_domain' => 'FOSUserBundle'
                ],
                'first_options' => [
                    'label' => 'form.password'
                ],
                'second_options' => [
                    'label' => 'form.password_confirmation'
                ],
                'invalid_message' => 'fos_user.password.mismatch',
            ])
            ->add('confirmation', CheckboxType::class, [
                'label' => 'Potwierdź zapoznanie się z regulaminem.',
                'required' => true,
                'value' => 0,
                'attr' => [
                    'class' => 'filled-in'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'liqster_user_registration';
    }
}

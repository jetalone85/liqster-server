<?php

namespace Liqster\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType;

/**
 * Class RegistrationType
 *
 * @package Liqster\UserBundle\Form
 */
class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //        $builder
        //            ->add(
        //                'name', TextType::class, [
        //                    'required' => true,
        //                    'label' => 'name',
        //                    'attr' => [
        //                        'class' => 'form-control'
        //                    ]
        //                ]
        //            );
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return RegistrationFormType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'liqster_user_registration';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getBlockPrefix();
    }
}

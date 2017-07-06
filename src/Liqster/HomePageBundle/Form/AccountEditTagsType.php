<?php

namespace Liqster\HomePageBundle\Form;

use Doctrine\DBAL\Types\TextType;
use Liqster\HomePageBundle\Controller\AccountController;
use Liqster\HomePageBundle\Controller\ApiController;
use Liqster\HomePageBundle\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('tagsText', ChoiceType::class, [
                'choice_loader' => new CallbackChoiceLoader(function () {
                    return ApiController::list();
                }),
                'required' => false,
                'attr' => [
                    'class' => 'js-example-tokenizer',
                ]
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

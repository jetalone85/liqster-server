<?php

namespace Liqster\HomePageBundle\Form;

use Cron\CronBundle\Entity\CronJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('schedule', ChoiceType::class, [
                'choices' => [
                    'Rano' => 'morning',
                    'W pracy' => 'work',
                    'Po południu' => 'afternoon',
                    'Wieczorem' => 'evening',
                    'Rano i po południu' => 'morningAndEvening',
                ],
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
                'data_class' => CronJob::class
            ]
        );
    }
}

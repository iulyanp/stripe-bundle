<?php

namespace IP\StripeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class StripeType
 * @package IP\StripeBundle\Form
 */
class StripeType extends AbstractType
{

    /**
     * Build form function.
     *
     * @param FormBuilderInterface $builder the formBuilder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, [
                'required' => true,
                'attr'     => [
                    'data-stripe' => 'number',
                ]
            ])
            ->add('security', TextType::class, [
                'required' => true,
                'attr'     => ['data-stripe' => 'cvc'],
            ])
            ->add('expiration_month', ChoiceType::class, [
                'required' => true,
                'attr'     => ['data-stripe' => 'exp_month'],
                'choices'  => array_combine(range(1, 12), range(1, 12)),
            ])
            ->add('expiration_year', ChoiceType::class, [
                'required' => true,
                'attr'     => ['data-stripe' => 'exp_year'],
                'choices'  => array_combine(range(date('Y'), 2025), range(date('Y'), 2025)),
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('token', HiddenType::class, [
                'data' => '',
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @throws Exception
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ip_stripe_stripe';
    }

}
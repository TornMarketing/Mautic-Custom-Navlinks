<?php

namespace MauticPlugin\CustomNavigationLinksBundle\Form\Type;

use MauticPlugin\MauticCrmBundle\Integration\ConnectwiseIntegration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Mautic\CoreBundle\Form\Type\FormButtonsType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class NavLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locaton = [
            'admin' => 'Admin',
            'main' => 'Primary'
        ];

        $navType = [
            'blank' => 'Blank',
            'iframe' => 'iFrame'
        ];

        $builder
            ->add('location', ChoiceType::class, [
                'label'             => 'plugin.customnavlinks.location',
                'required'          => true,
                'choices'           => array_flip($locaton), // Choice type expects labels as keys
                'attr'              => ['class' => 'form-control'],                
            ])
            ->add('order', TextType::class, [
                'label' => 'plugin.customnavlinks.order',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('name', TextType::class, [
                'label' => 'plugin.customnavlinks.label',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('icon', TextType::class, [
                'label' => 'plugin.customnavlinks.icon',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('url', TextType::class, [
                'label' => 'plugin.customnavlinks.url',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('navType', ChoiceType::class, [
                'label'             => 'plugin.customnavlinks.nav_type',
                'required'          => true,
                'choices'           => array_flip($navType), // Choice type expects labels as keys
                'attr'              => ['class' => 'form-control'],                
            ])
            ;
            
            $builder->add(
                'buttons',
                FormButtonsType::class
            );

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }    
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'custom_navigation_links';
    }
}
<?php


namespace BackendBundle\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType{

    public function getName()
    {
        return 'location';
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['province']=$options['province'];
        $view->vars['city']=$options['city'];
        $view->vars['value']=$options['value'];
        $view->vars['get_city']=$options['get_city'];
        $view->vars['province_select']=$options['province_select'];

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('label',false);
        $resolver->setDefined([
            'province',
            'city',
            'value',
            'province_select',
            'get_city',
        ]);
    }
}
<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',null,['label' => 'city.name'])
          ->add('provinceId', 'entity', array(
            'class' => 'BackendBundle:Province',
            'query_builder' => function ($repository) {
                return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
            },
            'property' => 'name',
            'label' => 'city.provinceId'
          ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\City'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_city';
    }


}

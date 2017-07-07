<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionType extends AbstractType {
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('name', null, ['label' => 'institution.name'])
      ->add('numberEstudent', null, ['label' => 'institution.number_estudent'])
      ->add('city_id', 'entity', array(
        'class' => 'BackendBundle:City',
        'query_builder' => function ($repository) {
          return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
        },
        'property' => 'name',
        'label' => 'institution.city_id',
      ))
      ->add('enable', null, ['label' => 'institution.enabled']);
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'BackendBundle\Entity\Institution',
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
    return 'backendbundle_institution';
  }


}

<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType {

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'UserBundle\Entity\User',
      'province' => [],
      'city' => [],
      'province_select' => -1,
      'value' => -1,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
    return 'userbundle_user';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('name', null, ['label' => 'user.name'])
      ->add('lastname', null, ['label' => 'user.lastname'])
      ->add('phone', null, ['label' => 'user.phone'])
      ->add('dni', null, ['label' => 'user.dni'])
      ->add('institutionId', 'entity', array(
        'class' => 'BackendBundle:Institution',
        'query_builder' => function ($repository) {
          return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
        },
        'property' => 'name',
        'label' => 'user.institutionId',
      ))
      ->add('educationallevelId', 'entity', array(
        'class' => 'BackendBundle:EducationalLevel',
        'query_builder' => function ($repository) {
          return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
        },
        'property' => 'title',
        'label' => 'user.educationallevelId',
      ))
      ->add('cityId', 'BackendBundle\Form\Type\LocationType', [
        'class' => 'BackendBundle:City',
        'province' => $options['province'],
        'city' => $options['city'],
        'value' => $options['value'],
        'province_select' => $options['province_select'],
        'get_city' => 'get_city',
        'allow_extra_fields' => true,
        //'choices'=>$options['location']
      ])
      ->add('username', null, ['label' => 'user.username'])
      ->add('email', null, ['label' => 'user.email'])
      ->add('roles', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
        'multiple' => true,
        'choices' => [
          'ROLE_ESTUDENT' => 'Estudiante',
          'ROLE_TEACHER' => 'Profesor',
          'ROLE_SUPER_ADMIN' => 'Administrador',
        ],
        'label' => 'user.roll',
      ])
      ->add('enabled', null, ['label' => 'user.enabled'])
      ->add('locked', null, ['label' => 'user.locked']);
  }


}

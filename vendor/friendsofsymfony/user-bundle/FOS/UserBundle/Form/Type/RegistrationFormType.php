<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
            'province' => [],
            'city' => [],
            'province_select' => -1,
            'value' => -1,
        ));
    }

    public function getName()
    {
        return 'fos_user_registration';
    }
}

<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',null,['label' => 'task.name'])
            ->add('desciption',null,['label' => 'task.desciption'])
            ->add('dateLimit',DateType::class,['label' => 'task.dateLimit'])
            ->add('institutionId', 'entity', array(
                'class' => 'BackendBundle:Institution',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
                },
                'property' => 'name',
                'label' => 'company.institutionId',
            ))
            ->add('educationallevelId', 'entity', array(
                'class' => 'BackendBundle:EducationalLevel',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
                },
                'property' => 'title',
                'label' => 'company.educationallevelId',
            ))
            ->add('userId',null,['label' => 'task.userId']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Task'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_province';
    }


}

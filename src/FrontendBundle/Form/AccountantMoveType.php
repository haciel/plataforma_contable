<?php

namespace FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountantMoveType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',null,['label' => 'accountantMove.date'])
            ->add('number',null,['label' => 'accountantMove.number'])
            ->add('slipeNumber',null,['label' => 'accountantMove.slipeNumber'])
            ->add('slipeId', 'entity', array(
                'class' => 'BackendBundle:SlipType',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
                },
                'property' => 'name',
                'label' => 'accountantMove.slipeId',
            ))
            ->add('description',null,['label' => 'accountantMove.description'])
            ->add('operations', CollectionType::class, array(
                'entry_type' => OperationsType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => true,
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\AccountantMove'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_accountantMove';
    }


}

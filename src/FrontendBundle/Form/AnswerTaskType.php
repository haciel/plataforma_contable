<?php

namespace FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerTaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file','BackendBundle\Form\Type\DocumentType',[
                'label' => 'answerTask.file',
                'url_upload'=>'upload_document',
                'url_show'=>'',
                'acceptFileTypes'=>['doc','docx','pdf','xls','xlsx']
            ])
        ->add('taskId',null,['label'=>'answerTask.taskId']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\AnswerTask'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'frontendbundle_answerTask';
    }


}

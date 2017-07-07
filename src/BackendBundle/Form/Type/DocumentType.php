<?php
namespace BackendBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType{

    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'document_type';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $acceptFileTypes=$options['acceptFileTypes'];
        if($acceptFileTypes) {
            $aux = $acceptFileTypes[0];
            for ($i = 1; $i < count($acceptFileTypes); $i++)
                $aux.='|'.$acceptFileTypes[$i];
            $acceptFileTypes=' /(\.|\/)('.$aux.')$/i';
        }
        else
            $acceptFileTypes='null';
        $view->vars['url_upload'] = $options['url_upload'];
        $view->vars['url_show'] = $options['url_show'];
        $view->vars['acceptFileTypes'] = $acceptFileTypes;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('url_upload', 'url_show'));
        $resolver->setDefaults(
            [
                'acceptFileTypes' => null,
                'attr' => ['style' => 'opacity: 0;width: 0; max-width: 0; height: 0; max-height: 0;']
            ]
        );
    }

}
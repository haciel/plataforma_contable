<?php

namespace BackendBundle\Form\Type;


use Symfony\Component\Form\AbstractType;

class CKeditorType extends AbstractType{

    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextareaType';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'ckeditor_type';
    }

}
<?php


namespace BackendBundle\Twig;

use BackendBundle\Useful\ManagerConvertDate;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;


class AppExtension extends \Twig_Extension {

    /** @var ContainerInterface */
    protected $container;

    /** @param ContainerInterface $container */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('yes_not', array($this, 'yesnotFilter'))
        );
    }

    public function yesnotFilter($value)
    {
        $translator_message=$value?'backend.yes':'backend.not';
        $translator_message=$this->container->get('translator')->trans($translator_message);
        $label_class=$value?'label-success':'label-danger';
        return '<span class="label '.$label_class.'">'.$translator_message.'</span>';
    }



    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'app_extension';
    }
}
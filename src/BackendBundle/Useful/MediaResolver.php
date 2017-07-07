<?php


namespace BackendBundle\Useful;

/**
 * Class BasePathResolver
 * @package BackendBundle\Useful
 */
class MediaResolver
{
    private $container;
    private $httpfoundation;

    function __construct($container, $httpfoundation)
    {
        $this->container = $container;
        $this->httpfoundation = $httpfoundation;
    }

    private function getParameter($namespace)
    {
        try {
            $names = preg_split('/\./', $namespace);
            $parameter = $this->container->getParameter($names[0]);

            for ($i = 1; $i < count($names); $i++) {
                $parameter = $parameter[$names[$i]];
            }

            return $parameter;
        } catch (\Exception $e) {
            throw new \Exception("No se encuentra el parametro " . $namespace);
        }
    }

    public function getRelBaseUrl($namespace)
    {
        return $this->getParameter('directories.relative') . $this->getParameter('directories.' . $namespace);
    }


    public function getRelRootPath($namespace)
    {
        return $this->getParameter('directories.global') . $this->getParameter('directories.' . $namespace);
    }

    public function getFsBasePath($namespace)
    {
        $kernel = $this->container->get('kernel');
        return $kernel->getRootDir()
        . '/../web/'
        . $this->getParameter('directories.relative')
        . $this->getParameter('directories.' . $namespace);
    }
}
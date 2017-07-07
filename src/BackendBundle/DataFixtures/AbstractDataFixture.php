<?php

namespace BackendBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractDataFixture extends AbstractFixture
    implements ContainerAwareInterface, FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $environment = $this->getCurrentEnvironment();

        // Check if the data fixture has a doAlways method and always run this in any environment
        if (method_exists($this, 'doAlways')) {
            $this->doAlways($manager);
        }

        // Check if the current environment in production and if the doProd method exists
        if ($environment == 'prod' && method_exists($this, 'doProd')) {
            $this->doProd($manager);
        }

        // Check if the current environment is available in the list of environments assigned to the data fixture
        if (in_array($environment, $this->getEnvironments())) {
            $this->doLoad($manager);
        }
    }

    /**
     * @return string
     */
    public function getCurrentEnvironment()
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->container->get('kernel');

        return $kernel->getEnvironment();
    }

    /**
     * Get the environments the data fixtures are ran on
     * @return array The name of the environments.
     */
    protected function getEnvironments()
    {
        return ['test', 'dev'];
    }

    /**
     * Perform the database objects
     * @param ObjectManager $manager The object manager.
     */
    abstract protected function doLoad(ObjectManager $manager);

    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
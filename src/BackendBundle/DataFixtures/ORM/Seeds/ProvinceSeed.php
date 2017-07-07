<?php

namespace BackendBundle\DataFixtures\ORM\Seeds;


use BackendBundle\DataFixtures\AbstractDataFixture;
use BackendBundle\Entity\Country;
use BackendBundle\Entity\Province;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProvinceSeed extends AbstractDataFixture
{

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * Perform the database objects
     * @param ObjectManager $manager The object manager.
     */
    protected function doLoad(ObjectManager $manager)
    {
        $country=$manager->getRepository('BackendBundle:Country')->findOneBy(
            array('name'=>'Cuba')
        );
        $entities = [
            [
                'name' => 'Matanzas',
                'countryId'=>$country
            ],
        ];
        foreach ($entities as $item) {
            $entity = new Province();
            $entity->setName($item['name']);
            $entity->setCountryId($item['countryId']);

            $manager->persist($entity);
            $manager->flush();
        }
    }

    /**
     * Get the environments the data fixtures are ran on
     * @return array The name of the environments.
     */
    protected function getEnvironments()
    {
        return ['dev'];
    }

}
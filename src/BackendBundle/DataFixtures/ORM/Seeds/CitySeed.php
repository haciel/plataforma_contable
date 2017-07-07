<?php

namespace BackendBundle\DataFixtures\ORM\Seeds;


use BackendBundle\DataFixtures\AbstractDataFixture;
use BackendBundle\Entity\City;
use BackendBundle\Entity\Country;
use BackendBundle\Entity\Province;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CitySeed extends AbstractDataFixture
{

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * Perform the database objects
     * @param ObjectManager $manager The object manager.
     */
    protected function doLoad(ObjectManager $manager)
    {
        $provinceId=$manager->getRepository('BackendBundle:Province')->findOneBy(
            array('name'=>'Matanzas')
        );
        $entities = [
            [
                'name' => 'Matanzas',
                'provinceId'=>$provinceId
            ],
        ];
        foreach ($entities as $item) {
            $entity = new City();
            $entity->setName($item['name']);
            $entity->setProvinceId($item['provinceId']);

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
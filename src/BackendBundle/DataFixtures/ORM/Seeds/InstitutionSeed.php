<?php

namespace BackendBundle\DataFixtures\ORM\Seeds;


use BackendBundle\DataFixtures\AbstractDataFixture;
use BackendBundle\Entity\City;
use BackendBundle\Entity\Country;
use BackendBundle\Entity\Institution;
use BackendBundle\Entity\Province;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InstitutionSeed extends AbstractDataFixture
{

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * Perform the database objects
     * @param ObjectManager $manager The object manager.
     */
    protected function doLoad(ObjectManager $manager)
    {
        $cityId=$manager->getRepository('BackendBundle:City')->findOneBy(
            array('name'=>'Matanzas')
        );
        $entities = [
            [
                'name' => 'Universidad de Matanzas',
                'cityId'=>$cityId,
                'numberEstudent'=>10000
            ],
        ];
        foreach ($entities as $item) {
            $entity = new Institution();
            $entity->setName($item['name']);
            $entity->setCityId($item['cityId']);
            $entity->setNumberEstudent($item['numberEstudent']);
            $entity->setEnable(true);
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
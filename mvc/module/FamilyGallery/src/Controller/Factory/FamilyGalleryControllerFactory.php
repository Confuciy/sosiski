<?php
namespace FamilyGallery\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use FamilyGallery\Controller\FamilyGalleryController;

class FamilyGalleryControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
//        echo '<pre>'; var_dump($entityManager); echo '</pre>';
//        die;

        return new FamilyGalleryController($entityManager);
    }
}
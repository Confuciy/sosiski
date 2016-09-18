<?php
namespace FamilyGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use FamilyGallery\Entity;

class FamilyGalleryController extends AbstractActionController
{

    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $member_id = (int) $this->params()->fromRoute('member_id', 0);
        if (!$member_id or empty($member_id)) {
            $galleries = $objectManager
                ->getRepository('\FamilyGallery\Entity\FamilyGallery')
                ->findBy(['state' => 1], ['year' => 'DESC', 'month' => 'DESC']);
            echo '<pre>'; print_r($galleries); echo '</pre>';
            die;
        }
        else {
            //
        }

        /*
        $galleries = $objectManager
            ->getRepository('\FamilyGallery\Entity\FamilyGallery')
            ->findBy([('state' => 1, 'member_id' => $member_id], ['year' => 'DESC', 'month' => 'DESC']);

        $galleries_array = [];
        foreach ($galleries as $gallery) {
            $galleries_array[] = $gallery->getArrayCopy();
        }

        $view = new ViewModel([
            'posts' => $galleries_array,
        ]);

        return $view;
        */
    }
}
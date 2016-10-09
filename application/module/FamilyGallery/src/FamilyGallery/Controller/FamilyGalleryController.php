<?php
namespace FamilyGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
#use Doctrine\ORM\Query;
use FamilyGallery\Entity;

class FamilyGalleryController extends AbstractActionController
{

    public function indexAction()
    {
//        return $this->forward()->dispatch('FamilyGallery\Controller\FamilyGalleryController', array('action' => 'view', 'member_id'   => 1));

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $member_id = (int) $this->params()->fromRoute('member_id');
        if (!$member_id or empty($member_id)) {
            #$galleries = $objectManager
            #    ->getRepository('\FamilyGallery\Entity\FamilyGallery')
            #    ->findBy(['state' => 1], ['year' => 'DESC', 'month' => 'DESC']);

            #$galleries_array = [];
            #$col = 0;
            #foreach ($galleries as $gallery) {
                /*
                $photos = $objectManager
                    ->getRepository('\FamilyGallery\Entity\FamilyGalleryPhoto')
                    ->findBy(['galleryId' => $gallery->getId(), 'state' => 1], ['sortby' => 'DESC']);
                $photos_array = [];
                foreach ($photos as $photo) {
                    $photos_array[] = $photo->getArrayCopy();
                }
                */

                #$member = $objectManager
                #   ->getRepository('\FamilyGallery\Entity\FamilyGalleryMember')
                #   ->findBy(['id' => 1]);

                #$galleries_array[$col]['member'] = $member;
                #$galleries_array[] = $gallery->getArrayCopy();
                #$galleries_array[$col]['photos'] = $photos_array;
                #$col++;
            #}

            #$this->flashMessenger()->addErrorMessage('asdasdsad');

            /*
            $galleries = $objectManager
                ->createQuery('SELECT f, m FROM \FamilyGallery\Entity\FamilyGallery f
                    JOIN f.member m where f.state = 1 order by f.year desc, f.month desc')
                ->getArrayResult();
            */

            $members = $objectManager
                ->createQuery('SELECT distinct m FROM \FamilyGallery\Entity\FamilyGalleryMember m
                    JOIN \FamilyGallery\Entity\FamilyGallery f where m.state = 1 AND f.state = 1')
                ->getArrayResult();

            #echo '<pre>'; print_r($members); echo '</pre>';
            #die;
        }

        $view = new ViewModel([
            'members' => $members,
        ]);

        return $view;
    }

    public function viewAction()
    {
        $member_id = (int)$this->params()->fromRoute('member_id');
        if ($member_id or !empty($member_id)) {
            $view = new ViewModel([
                'member_id' => $member_id,
            ]);

            return $view;
        }
    }
}
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
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $member_id = (int) $this->params()->fromRoute('member_id', 0);
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

            $this->flashMessenger()->addErrorMessage('asdasdsad');


            $galleries = $objectManager
                ->createQuery('SELECT f, m FROM \FamilyGallery\Entity\FamilyGallery f LEFT JOIN f.member m where f.state = 1 order by f.year desc, f.month desc')
                ->getArrayResult();

            #echo '<pre>'; print_r($galleries_array); echo '</pre>';
            #die;
        }
        else {
            //
        }

        $view = new ViewModel([
            'galleries' => $galleries,
        ]);

        return $view;

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
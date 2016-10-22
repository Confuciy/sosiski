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

        $member_id = (int)$this->params()->fromRoute('member_id');
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
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $member_id = (int)$this->params()->fromRoute('member_id');
        $year = (int)$this->params()->fromRoute('year');
        $month = (int)$this->params()->fromRoute('month');

        if (!empty($member_id)) {
            $member = $objectManager
                ->createQuery('SELECT m FROM \FamilyGallery\Entity\FamilyGalleryMember m
                where m.id = ' . $member_id)
                ->getResult();
        }

        if (!empty($member_id) and empty($year) and empty($month)) {
            $res_years_months = $objectManager
                ->createQuery('SELECT distinct f.year, f.month FROM \FamilyGallery\Entity\FamilyGallery f
                JOIN \FamilyGallery\Entity\FamilyGalleryMember m
                where f.state = 1 and m.id = ' . $member_id . ' order by f.year desc, f.month desc')
                ->getArrayResult();
            $years = [];
            $months = [];
            if (sizeof($res_years_months)) {
                foreach ($res_years_months as $row) {
                    $years[] = $row['year'];
                    $months[$row['year']][] = $row['month'];
                }
                $years = array_unique($years);
            }

            $view = new ViewModel([
                'member_id' => $member_id,
                'member' => $member,
                'years' => $years,
                'months' => $months
            ]);

            $view->setTemplate('family-gallery/family-gallery/select-years');

            return $view;
        }
        if (!empty($member_id) and !empty($year) and !empty($month)) {
            list($gallery) = $objectManager
                ->createQuery('SELECT g FROM \FamilyGallery\Entity\FamilyGallery g
                JOIN \FamilyGallery\Entity\FamilyGalleryMember m
                where m.id = ' . $member_id.' and g.year = ' . $year . '  and g.month = ' . $month)
                ->getArrayResult();

            $photos = $objectManager
                ->createQuery('SELECT p.id, p.name, p.info, p.date, p.state, p.path, p.sortby
                FROM \FamilyGallery\Entity\FamilyGalleryPhoto p
                where p.galleryId = ' . $gallery['id'] . ' 
                and p.state = 1                
                order by p.sortby ASC')
                ->getArrayResult();
            #echo '<pre>'; print_r($photos); echo '</pre>';
            #die;

            $view = new ViewModel([
                'member_id' => $member_id,
                'member' => $member,
                'year' => $year,
                'month' => $month,
                'gallery' => $gallery,
                'photos' => $photos
            ]);

            return $view;
        }
    }
}
<?php
namespace FamilyGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FamilyGalleryController extends AbstractActionController
{

    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $member_id = (int)$this->params()->fromRoute('member_id');
        if (!$member_id or empty($member_id)) {
//            $members = $this->entityManager
//                ->createQuery('SELECT distinct m FROM \FamilyGallery\Entity\FamilyGalleryMember m
//                    JOIN \FamilyGallery\Entity\FamilyGallery f where m.state = 1 AND f.state = 1')
//                ->getArrayResult();
            $members = $this->entityManager
                ->createQuery('SELECT distinct m FROM \FamilyGallery\Entity\FamilyGalleryMember m where m.state = 1')
                ->getArrayResult();
        }

        //$this->layout('layout/dopetrope-family-gallery');
        $this->layout('layout/future-imperfect-simple');
        $view = new ViewModel([
            'members' => $members,
        ]);

        return $view;
    }

    public function viewAction()
    {
        $member_id = (int)$this->params()->fromRoute('member_id');
        $year = (int)$this->params()->fromRoute('year');
        $month = (int)$this->params()->fromRoute('month');

        if (!empty($member_id)) {
            $member = $this->entityManager
                ->createQuery('SELECT m FROM \FamilyGallery\Entity\FamilyGalleryMember m
                where m.id = ' . $member_id)
                ->getArrayResult();
        }

        if (!empty($member_id) and empty($year) and empty($month)) {
            $res_years_months = $this->entityManager
                ->createQuery('SELECT distinct f.year, f.month FROM \FamilyGallery\Entity\FamilyGallery f
                where f.state = 1 and f.memberId = ' . $member_id . ' order by f.year desc, f.month desc')
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

            //$this->layout('layout/family-gallery-dopetrope');
            $this->layout('layout/future-imperfect-simple');
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
            list($gallery) = $this->entityManager
                ->createQuery('SELECT g FROM \FamilyGallery\Entity\FamilyGallery g
                where g.memberId = ' . $member_id.' and g.year = ' . $year . '  and g.month = ' . $month)
                ->getArrayResult();

            $photos = $this->entityManager
                ->createQuery('SELECT p.id, p.name, p.info, p.date, p.state, p.path, p.sortby
                FROM \FamilyGallery\Entity\FamilyGalleryPhoto p
                where p.galleryId = ' . $gallery['id'] . ' 
                and p.state = 1                
                order by p.sortby ASC')
                ->getArrayResult();

            $this->layout('layout/family-gallery-lens');
            //$this->layout('layout/family-gallery-multiverse');
            $view = new ViewModel([
                'member_id' => $member_id,
                'member' => $member,
                'year' => $year,
                'month' => $month,
                'gallery' => $gallery,
                'photos' => $photos
            ]);
            $view->setTemplate('family-gallery/family-gallery/view');

            $header = new ViewModel([
                'member_id' => $member_id,
                'member' => $member,
                'year' => $year,
                'month' => $month
            ]);
            $header->setTemplate('family-gallery/family-gallery/view-header');
            $view->addChild($header, 'header');

            return $view;
        }
    }
}
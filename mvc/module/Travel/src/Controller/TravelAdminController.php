<?php
namespace Travel\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for travel management (adding, editing, viewing travels).
 */
class TravelAdminController extends AbstractActionController
{

    /**
     * Database Adapter.
     * @var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;

    /**
     * Travel manager.
     * @var User\Service\TravelManager
     */
    private $travelManager;

    /**
     * Constructor.
     */
    public function __construct($dbAdapter, $travelManager)
    {
        $this->dbAdapter = $dbAdapter;
        $this->travelManager = $travelManager;
    }

    public function indexAction()
    {
        $page = $this->params()->fromRoute('id');
        if(empty($page)){
            $page = 1;
        }

        // Get travels list
        $travels = $this->travelManager->getTravelsList($page);

        $this->layout('layout/admin');
        $view = new ViewModel([
            'travels'   => $travels,
            'page'      => $page,
            'pages'     => $this->travelManager->getTravelsPages(),
        ]);
        $view->setTemplate('travel/travel-admin/index');

        return $view;
    }

    public function editAction(){
        // Get langs
        $langs = $this->travelManager->getLangs();

        // Get travel
        $travel = $this->travelManager->getTravelForEdit($this->params()->fromRoute('id'), $langs);

        $this->layout('layout/admin');
        $view = new ViewModel([
            'travel'    => $travel,
            'langs'     => $langs
        ]);
        $view->setTemplate('travel/travel-admin/edit');

        return $view;
    }
}
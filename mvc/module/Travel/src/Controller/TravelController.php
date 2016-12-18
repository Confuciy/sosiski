<?php
namespace Travel\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for user management (adding, editing,
 * viewing users and changing user's password).
 */
class TravelController extends AbstractActionController
{

    /**
     * dbAdapter
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

    /**
     * This is the default "index" action of the controller. It displays the
     * list of users.
     */
    public function indexAction()
    {
        //die('TravelController -> indexAction');

        // Current page
        //$page = $this->params('page');

        // Get travels list
        $travels = $this->travelManager->getTravelsList($page);

        $this->layout('layout/future-imperfect-simple');
        $view = new ViewModel([
            'travels' => $travels,
            'pages' => $this->travelManager->getTravelsPages(),
        ]);
        $view->setTemplate('travel/travel/index');

        return $view;
    }
}
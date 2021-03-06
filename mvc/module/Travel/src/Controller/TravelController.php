<?php
namespace Travel\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Router\RoutePluginManager;
use Zend\View\Model\ViewModel;

class TravelController extends AbstractActionController
{

    /**
     * dbAdapter
     * @var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;

    /**
     * Travel manager.
     * @var Travel\Service\TravelManager
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
     * list of travels.
     */
    public function indexAction($page = [])
    {
        // Current page
        if (!sizeof($page)) {
            $page = (int)$this->params()->fromRoute('page');
        } else {
            $page = $page['page'];
        }

        // Get travels list
        $travels = $this->travelManager->getTravelsList($page);

        $this->layout('layout/future-imperfect-simple');
        $view = new ViewModel([
            'travels' => $travels,
            'page'  => $page,
            'pages' => $this->travelManager->getTravelsPages(),
        ]);
        $view->setTemplate('travel/travel/index');

        return $view;
    }

    /**
     * List mini-post of travels
     */
    public function miniPostsAction($limit = [])
    {
        // Get travels list
        $travels = $this->travelManager->getTravelsMiniPostsList($limit['limit']);

        $this->layout('layout/future-imperfect-simple');
        $view = new ViewModel([
            'travels' => $travels,
        ]);
        $view->setTemplate('travel/travel/mini-posts');

        return $view;
    }

    public function viewAction()
    {
        // Current url
        $url = $this->params('url');

        // Get travel by URL
        $travel = $this->travelManager->getTravelByUrl($url);

        $this->layout('layout/future-imperfect-simple');
        $view = new ViewModel([
            'travel' => $travel,
        ]);
        $view->setTemplate('travel/travel/view');

        return $view;
    }
}
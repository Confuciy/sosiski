<?php
namespace Travel\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Travel\Form\TravelEditForm;

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

    private $uploadPath = '';

    /**
     * Constructor.
     */
    public function __construct($dbAdapter, $travelManager, $uploadPath)
    {
        if (isset($_SESSION['Zend_Auth']->session) and $_SESSION['Zend_Auth']->session != 'gorbachev.info@gmail.com') {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->dbAdapter = $dbAdapter;
        $this->travelManager = $travelManager;
        $this->uploadPath = $uploadPath;
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

        $travel_id = $this->params()->fromRoute('id');

        // Get travel
        $travel = $this->travelManager->getTravelForEdit($travel_id, $langs);

        // Create travel edit form
        $form = new TravelEditForm($this->dbAdapter, $travel, $this->uploadPath);
        $form->setAttribute('action', '/travels/admin/edit/'.$travel_id);

        // Check if travel has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data and FILES data
            $data = array_merge_recursive((array)$this->params()->fromPost(), (array)$this->params()->fromFiles());

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                //$data = $form->getData();

                // Update the travel.
                $this->travelManager->updateTravel($travel, $data);

                // Redirect to "edit" page
                return $this->redirect()->toRoute('travels_admin', ['action' => 'edit', 'id' => $travel_id]);
            }
        } else {
            $form->setData(array(
                'travel'    => $travel,
            ));
        }

        // Get travel's images
        $images = $this->travelManager->getTravelImages($travel_id);

        $this->layout('layout/admin');
        $view = new ViewModel([
            'travel'    => $travel,
            'langs'     => $langs,
            'images'    => $images,
            'form' => $form
        ]);
        $view->setTemplate('travel/travel-admin/edit');

        return $view;
    }
}
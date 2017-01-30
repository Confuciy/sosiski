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

    private $userManager;

    private $translator;

    /**
     * Constructor.
     */
    public function __construct($dbAdapter, $travelManager, $userManager, $translator)
    {
//        if (isset($_SESSION['Zend_Auth']->session) and $_SESSION['Zend_Auth']->session != 'gorbachev.info@gmail.com') {
//            $this->getResponse()->setStatusCode(404);
//            return;
//        }

        $this->dbAdapter = $dbAdapter;
        $this->travelManager = $travelManager;
        $this->userManager = $userManager;
        $this->translator = $translator;

        // Find a user with such Email.
        $user = $this->userManager->getUserByEmail($_SESSION['Zend_Auth']->session);
        $user_roles = $this->userManager->getUserRolesIds($user['id']);

        // If user not Administrator
        if (!in_array(4, $user_roles)) {
            throw new \Exception($this->translator->translate('You\'re not administrator'));
        }
    }

    public function indexAction()
    {
        $page = $this->params()->fromRoute('id');
        if(empty($page)){
            $page = 1;
        }

        // Get travels list
        $travels = $this->travelManager->getTravelsList($page, 1);

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
        $form = new TravelEditForm($this->dbAdapter, $travel, $this->travelManager->getUploadPath());
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
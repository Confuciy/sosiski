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

    private $user_id = 0;

    /**
     * Constructor.
     */
    public function __construct($dbAdapter, $travelManager, $userManager, $translator)
    {
        if (!isset($_SESSION['Zend_Auth']->session)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

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
        } else {
            $this->user_id = $user['id'];
        }
    }

    public function indexAction()
    {
        $page = $this->params()->fromRoute('id');
        if (empty($page)) {
            $page = 1;
        }

        // Get travels list
        $travels = $this->travelManager->getTravelsList($page, 1);
        if (sizeof($travels) > 0) {
            foreach($travels as $key => &$travel){
                $travels[$key]['images_size'] = $this->travelManager->getTravelImagesSize($travel['travel_id']);
            }
        }

        $this->layout('layout/admin');
        $view = new ViewModel([
            'travels' => $travels,
            'page' => $page,
            'pages' => $this->travelManager->getTravelsPages(),
        ]);
        $view->setTemplate('travel/travel-admin/index');

        return $view;
    }

    public function editAction()
    {
        // Get langs
        $langs = $_SESSION['langs'];

        $travel_id = $this->params()->fromRoute('id');

        // Get travel
        $travel = $this->travelManager->getTravelForEdit($travel_id, $langs);

        // Create travel edit form
        $form = new TravelEditForm('edit', $this->dbAdapter, $travel, $this->translator, $this->travelManager->getUploadPath());
        $form->setAttribute('action', '/travels/admin/edit/' . $travel_id);

        // Check if travel has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data and FILES data
            $data = array_merge_recursive((array)$this->params()->fromPost(), (array)$this->params()->fromFiles());

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                //$data = $form->getData();

                // Update the travel.
                $this->travelManager->editTravel($travel, $data);

                // Redirect to "edit" page
                return $this->redirect()->toRoute('travels_admin', ['action' => 'edit', 'id' => $travel_id]);
            }
        } else {
            $form->setData(array(
                'travel' => $travel,
            ));
        }

        // Get travel's images
        $images = $this->travelManager->getTravelImages($travel_id);

        $this->layout('layout/admin');
        $view = new ViewModel([
            'travel' => $travel,
            'langs' => $langs,
            'images' => $images,
            'form' => $form
        ]);
        $view->setTemplate('travel/travel-admin/edit');

        return $view;
    }

    public function createAction()
    {
        // Create travel edit form
        $form = new TravelEditForm('create', $this->dbAdapter, null, $this->translator, $this->travelManager->getUploadPath());
        $form->setAttribute('action', '/travels/admin/create/');

        // Check if travel has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data and FILES data
            $data = array_merge_recursive((array)$this->params()->fromPost(), (array)$this->params()->fromFiles());

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Create the travel.
                $travel_id = $this->travelManager->createTravel($this->user_id, $data);

                if (!empty($travel_id)) {
                    // Redirect to "edit" page
                    return $this->redirect()->toRoute('travels_admin', ['action' => 'edit', 'id' => $travel_id]);
                } else {
                    throw new \Exception($this->translator->translate('Cannot create travel!'));
                }
            }
        }

        $this->layout('layout/admin');
        $view = new ViewModel([
            'form' => $form
        ]);
        $view->setTemplate('travel/travel-admin/create');

        return $view;
    }

    public function deleteAction()
    {
        $travel_id = $this->params()->fromRoute('id');

        if (!empty($travel_id)) {
            $this->travelManager->deleteTravel($travel_id);

            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Travel was deleted successfully.'));

            // Redirect to "edit" page
            return $this->redirect()->toRoute('travels_admin');
        } else {
            throw new \Exception($this->translator->translate('Cannot delete travel!'));
        }

        $view = new ViewModel();
        $view->setTerminal(true);

        return $view;
    }
}
<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Barcode\Barcode;
use Zend\Mvc\MvcEvent;

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class IndexController extends AbstractActionController
{
    /**
     * dbAdapter.
     * @var var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;

    //private $authService;

    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        //$this->authService = $authService;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * Home page.
     */
    public function indexAction()
    {
        $this->layout('layout/future-imperfect');

        return new ViewModel();
    }

    /**
     * This is the "about" action. It is used to display the "About" page.
     */
    public function aboutAction()
    {
        $appName = 'User Demo';
        $appDescription = 'This demo shows how to implement user management with Zend Framework 3';

        // Return variables to view script with the help of
        // ViewObject variable container
        return new ViewModel([
            'appName' => $appName,
            'appDescription' => $appDescription
        ]);
    }

    /**
     * The "settings" action displays the info about currently logged in user.
     */
    public function settingsAction()
    {
//        $user = $this->entityManager->getRepository(User::class)
//            ->findOneByEmail($this->identity());
//
//        if ($user == null) {
//            throw new \Exception('Not found user with such email');
//        }
//
//        return new ViewModel([
//            'user' => $user
//        ]);
    }

    public function changeLanguageAction(){
        // Get locale param from route
        $locale = (string)$this->params()->fromRoute('locale', '');
        if ($locale != '') {
            // Set cookie with locale
            setcookie('locale', $locale, time() + 2592000, '/', $_SERVER['HTTP_HOST']);
            $_SESSION['locale'] = $locale;
            setlocale(LC_ALL, $locale.".UTF-8");
        }

        // Go to prefer page
        return $this->redirect()->toUrl((isset($_SERVER['HTTP_REFERER']) and $_SERVER['HTTP_REFERER'] != '')?$_SERVER['HTTP_REFERER']:'/');
    }
}

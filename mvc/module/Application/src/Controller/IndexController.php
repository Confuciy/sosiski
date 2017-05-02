<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\PayAmountForm;
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
    private $paymentManager;
    private $translator;
    //private $authService;

    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($dbAdapter, $paymentManager, $translator)
    {
        $this->dbAdapter = $dbAdapter;
        $this->paymentManager = $paymentManager;
        $this->translator = $translator;
        //$this->authService = $authService;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * Home page.
     */
    public function indexAction()
    {
        $page = (int)$this->params()->fromRoute('page');

        $this->layout('layout/future-imperfect');
        $this->layout()->setVariable('page', $page);

        return new ViewModel();
    }

    /**
     * This is the "about" action. It is used to display the "About" page.
     */
    public function aboutAction()
    {
        $pay_amount = 0;
        $pay_result = [];

        // Create Pay Amount form
        $form = new PayAmountForm($this->translator);
        $form->setAttribute('action', '/about/');

        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data and FILES data
            $data = array_merge_recursive((array)$this->params()->fromPost(), (array)$this->params()->fromFiles());

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                $pay_amount = $data['pay_amount'];
                $pay_result = $this->paymentManager->pay($pay_amount);
            }
        }

        $this->layout('layout/future-imperfect-simple');
        return new ViewModel([
            'appName' => $appName,
            'appDescription' => $appDescription,
            'form' => $form,
            'pay_amount' => $pay_amount,
            'pay_result' => $pay_result
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

    /**
     * Change language
     * @return \Zend\Http\Response
     */
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

    public function tagAction()
    {
        $tag_name = (string)$this->params()->fromRoute('tag_name');
        die(rawurldecode($tag_name));
    }
}

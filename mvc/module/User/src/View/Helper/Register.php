<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
//use Zend\View\Model\ViewModel;

class Register extends AbstractHelper
{
    //protected $dbAdapter = null;
    protected $user;

    //public function __construct(Register $meteoService)
    //public function __construct(ContainerInterface $container)
    public function __construct($user)
    {
        $this->user = $user;
        //$this->dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
    }

    public function getRegisterForm(){

        $register = $this->user->registerAction();
        $register->setTemplate('user/user/register');

        //$renderer = new \Zend\View\Renderer\PhpRenderer();
        //$r = $renderer->render($register);
        $r = $this->getView()->render($register);

//        echo '<pre>'; var_dump($register);
//        die;

        return $r;
    }

    /*
    public function __invoke()
    {
        //$temperature = $this->service->getTemperature($city);


        //$form = new UserForm('create', $this->dbAdapter);

        return $this->userController; //\User\Controller\UserController::getMethodFromAction('register');
        //return $this->getView()->render('user/user/register', ['form' => $form]);

        // If a full template is overkill, you could of course just render
        // the widget directly
        // return ">div>The temperature is $temperature degrees>/div>";
    }
    */
}
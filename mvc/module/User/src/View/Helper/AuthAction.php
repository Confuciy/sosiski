<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AuthAction extends AbstractHelper
{
    protected $authController;

    public function __construct($authController)
    {
        $this->authController = $authController;
    }

    public function action($action, $params = [])
    {
        // на странице авторизации, не выводим авторизацию
        if(trim($_SERVER['REQUEST_URI'], '/') == 'login' and $action == 'login'){
            return '';
        }

        $action = $action.'Action';
        return $this->getView()->render($this->authController->{$action}());
    }
}

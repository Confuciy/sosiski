<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AuthAction extends AbstractHelper
{
    protected $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    public function action($action, $params = [])
    {
        // на странице авторизации, не выводим авторизацию
        if(trim($_SERVER['REQUEST_URI'], '/') == 'login' and $action == 'login'){
            return '';
        }

        $action = $action.'Action';
        return $this->getView()->render($this->auth->{$action}());
    }
}

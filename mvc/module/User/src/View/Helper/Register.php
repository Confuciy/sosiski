<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Register extends AbstractHelper
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getRegisterForm(){

        $register = $this->user->registerAction();
        $register->setTemplate('user/user/register');

        $html = $this->getView()->render($register);
        return $html;
    }

    public function getAuthForm(){
        //
    }
}
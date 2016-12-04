<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class UserAction extends AbstractHelper
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function action($action, $params = [])
    {
        $action = $action.'Action';
        return $this->getView()->render($this->user->{$action}());
    }
}

<?php
namespace Travel\View\Helper;

use Zend\View\Helper\AbstractHelper;

class TravelAction extends AbstractHelper
{
    protected $travelController;

    public function __construct($travelController)
    {
        $this->travelController = $travelController;
    }

    public function action($action, $params = [])
    {
        $action = $action.'Action';
        return $this->getView()->render($this->travelController->{$action}());
    }
}
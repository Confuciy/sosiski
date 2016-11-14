<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private $user_auth = 0;
    private $user_email = '';

    public function init(){
        $sm = $app->getServiceManager();
        $auth = $sm->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $this->user_auth = 1;
            $this->user_email = $auth->getIdentity()->getEmail();
        }
    }

    public function indexAction()
    {
        $this->layout('layout/future_imperfect');

        $view = new ViewModel([
            'user_auth' => $this->user_auth,
            'user_email' => $this->user_email,
        ]);

        return $view;

        //return new ViewModel();
    }
}

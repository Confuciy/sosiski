<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\AbstractManager;
use \Zend\Mvc\I18n\Translator;
//use Zend\Authentication\AuthenticationService;

class Module
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * This method is called once the MVC bootstrapping is complete.
     */
    public function onBootstrap(MvcEvent $event)
    {

        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        // The following line instantiates the SessionManager and automatically
        // makes the SessionManager the 'default' one to avoid passing the
        // session manager as a dependency to other models.

//        $save_path = ini_get('session.save_path');
//        if(isset($_COOKIE['PHPSESSID']) and file_exists($save_path.'/'.$_COOKIE['PHPSESSID'])) {
//            echo $save_path . '<br />';
//        } else {
//            setcookie('PHPSESSID', '', time() - 2592000, '/', $_SERVER['HTTP_HOST']);
//            setcookie('user_hash', '', time() - 2592000, '/', $_SERVER['HTTP_HOST']);
//
//        }
//        echo '<pre>_SESSION<br />'; print_r($_SESSION); echo '</pre>';
//        echo '<pre>_COOKIE<br />'; print_r($_COOKIE); echo '</pre>';
//        die;

        // Set translate locale
        try {
            $locale = $event->getApplication()->getServiceManager()->get('Config')['translator']['locale'];
            if(isset($locale) and isset($_COOKIE['locale']) and $_COOKIE['locale'] != '' and $_COOKIE['locale'] != $locale) {
                $translator = $event->getApplication()->getServiceManager()->get(Translator::class);
                $translator->setLocale($_COOKIE['locale'])->setFallbackLocale($locale);
            }
            if(!isset($_SESSION['locale']) or $_SESSION['locale'] == ''){
                if(isset($_COOKIE['locale']) and $_COOKIE['locale'] != '') {
                    $_SESSION['locale'] = $_COOKIE['locale'];
                } else {
                    $_SESSION['locale'] = $locale;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        // Forget bad session
        $sessionManager = $serviceManager->get(SessionManager::class);
        $this->forgetInvalidSession($sessionManager);
    }

    protected function forgetInvalidSession(AbstractManager $sessionManager) {
        try {
            $sessionManager->start();
            return;
        } catch (\Exception $e) {

        }

        session_unset();
    }
}

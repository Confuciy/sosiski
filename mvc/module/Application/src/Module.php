<?php
namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\AbstractManager;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\I18n\Translator;
use \User\Service\AuthAdapter;

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
        $eventManager = $application->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

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

        // Error processing
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onError'], 100);

        // Set translate locale
        try {
            $locale = $serviceManager->get('Config')['translator']['locale'];
            if(isset($locale) and isset($_COOKIE['locale']) and $_COOKIE['locale'] != '' and $_COOKIE['locale'] != $locale) {
                $translator = $serviceManager->get(Translator::class);
                $translator->setLocale($_COOKIE['locale']); //->setFallbackLocale($locale);
            }
            if(!isset($_SESSION['locale']) or $_SESSION['locale'] == ''){
                if(isset($_COOKIE['locale']) and $_COOKIE['locale'] != '') {
                    $_SESSION['locale'] = $_COOKIE['locale'];
                    $locale = $_COOKIE['locale'];
                } else {
                    $_SESSION['locale'] = $locale;
                }
            }
            setlocale(LC_ALL, $locale.".UTF-8");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        // Forget bad session
        $sessionManager = $serviceManager->get(SessionManager::class);
        $this->forgetInvalidSession($sessionManager);

        // Authenticate user
        $authenticationService = $serviceManager->get(AuthenticationService::class);

        // Authenticate user if he has a cookie with authentication info
        if (!$authenticationService->hasIdentity() and isset($_COOKIE['user_hash']) and $_COOKIE['user_hash'] != ''
            and !isset($_POST['email']) and !isset($_POST['password']) and !isset($_POST['remember_me'])) {

            $authAdapter = $serviceManager->get(AuthAdapter::class);

            $decrypt = $authAdapter->getRSAdecode();

            $authAdapter->setEmail($decrypt['email']);
            $authAdapter->setPassword($decrypt['password']);

            $authenticationService->authenticate();
        }
    }

    protected function forgetInvalidSession(AbstractManager $sessionManager) {
        try {
            $sessionManager->start();
            return;
        } catch (\Exception $e) {

        }

        session_unset();
    }

    // Event listener method.
    public function onError(MvcEvent $event)
    {
        // Get the exception information.
        $exception = $event->getParam('exception');
        if ($exception!=null) {
            $exceptionName = $exception->getMessage();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $stackTrace = $exception->getTraceAsString();
        }
        $errorMessage = $event->getError();
        $controllerName = $event->getController();

        $event->getViewModel()->setTemplate('error/index');

        // Prepare email message.
//        $to = 'confuciy@yandex.rux';
//        $subject = 'Your Website Exception';
//
//        $body = '';
//        if(isset($_SERVER['REQUEST_URI'])) {
//            $body .= "Request URI: " . $_SERVER['REQUEST_URI'] . "\n\n";
//        }
//        $body .= "Controller: $controllerName\n";
//        $body .= "Error message: $errorMessage\n";
//        if ($exception!=null) {
//            $body .= "Exception: $exceptionName\n";
//            $body .= "File: $file\n";
//            $body .= "Line: $line\n";
//            $body .= "Stack trace:\n\n" . $stackTrace;
//        }
//
//        $body = str_replace("\n", "<br>", $body);
//
//        // Send an email about the error.
//        mail($to, $subject, $body);
    }
}

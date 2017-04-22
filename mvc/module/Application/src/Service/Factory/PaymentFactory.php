<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\PaymentManager;
use Application\Service\Payment\{Bank, Paypal, Bitcoin};

class PaymentFactory
{
    /**
     * This method creates the NavManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $translator = $container->get('Zend\Mvc\I18n\Translator');
        $bank = new Bank($translator, 100);          // Bank with balance 100
        $paypal = new Paypal($translator, 200);      // Paypal with balance 200
        $bitcoin = new Bitcoin($translator, 300);    // Bitcoin with balance 300

        return new PaymentManager($bank, $paypal, $bitcoin);
    }
}

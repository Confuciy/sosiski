<?php

/**
 * Class customException
 */
class customException extends Exception
{
    /**
     * error message
     *
     * @return string
     */
    public function errorMessage()
    {
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile() . ': <b>' . $this->getMessage() . '</b> is not a valid E-Mail address';
        return $errorMsg;
    }
}

/**
 * Какой-нибудь файл конфигурации
 */
class Config
{
    public static $factory = 1;
}

/**
 * Какой-то продукт
 */
interface Product
{

    /**
     * Возвращает название продукта
     *
     * @return string
     */
    public function getName();
}

/**
 * Абстрактная фабрика
 */
abstract class AbstractFactory
{

    /**
     * Возвращает фабрику
     *
     * @return AbstractFactory - дочерний объект
     * @throws Exception
     */
    public static function getFactory()
    {
        switch (Config::$factory) {
            case 1:
                return new FirstFactory();
            case 2:
                return new SecondFactory();
            default: die('Bad config');
        }
    }

    /**
     * Возвращает продукт
     *
     * @return Product
     */
    abstract public function getProduct();
}

/*
 * =====================================
 *             FIRST FAMILY
 * =====================================
 */

class FirstFactory extends AbstractFactory
{

    /**
     * Возвращает продукт
     *
     * @return Product
     */
    public function getProduct()
    {
        return new FirstProduct();
    }
}

/**
 * Продукт первой фабрики
 */
class FirstProduct implements Product
{

    /**
     * Возвращает название продукта
     *
     * @return string
     */
    public function getName()
    {
        return 'The product from the first factory';
    }
}

/*
 * =====================================
 *             SECOND FAMILY
 * =====================================
 */

class SecondFactory extends AbstractFactory
{

    /**
     * Возвращает продукт
     *
     * @return Product
     */
    public function getProduct()
    {
        return new SecondProduct();
    }
}

/**
 * Продукт второй фабрики
 */
class SecondProduct implements Product
{

    /**
     * Возвращает название продукта
     *
     * @return string
     */
    public function getName()
    {
        return 'The product from second factory';
    }
}

/*
 * =====================================
 *       USING OF ABSTRACT FACTORY
 * =====================================
 */

$firstProduct = AbstractFactory::getFactory()->getProduct();
Config::$factory = 20;
$secondProduct = AbstractFactory::getFactory()->getProduct();

print_r($firstProduct->getName());
// The first product from the first factory
echo '<br />';
print_r($secondProduct->getName());
// Second product from second factory

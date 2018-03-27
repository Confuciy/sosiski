<?php
/**
 * Фабрика
 */
interface Factory
{

    /**
     * Возвращает продукт
     *
     * @return Product
     */
    public function getProduct();
}

/**
 * Продукт
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
 * Первая фабрика
 */
class FirstFactory implements Factory
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
 * Вторая фабрика
 */
class SecondFactory implements Factory
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
 * Первый продукт
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
        return 'The first product';
    }
}

/**
 * Второй продукт
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
        return 'Second product';
    }
}

/*
 * =====================================
 *        USING OF FACTORY METHOD
 * =====================================
 */

$factory = new FirstFactory();
$firstProduct = $factory->getProduct();
$factory = new SecondFactory();
$secondProduct = $factory->getProduct();

print_r($firstProduct->getName());
// The first product
echo '<br />';
print_r($secondProduct->getName());
// Second product
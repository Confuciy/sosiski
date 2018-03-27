
<?php
/**
 * Пул объектов
 */
class Factory
{

    /**
     * @var Product[]
     */
    protected static $products = array();


    /**
     * Добавляет продукт в пул
     *
     * @param Product $product
     * @return void
     */
    public static function pushProduct(Product $product)
    {
        self::$products[$product->getId()] = $product;
    }

    /**
     * Возвращает продукт из пула
     *
     * @param integer|string $id - идентификатор продукта
     * @return Product $product
     */
    public static function getProduct($id)
    {
        return isset(self::$products[$id]) ? self::$products[$id] : null;
    }

    /**
     * Удаляет продукт из пула
     *
     * @param integer|string $id - идентификатор продукта
     * @return void
     */
    public static function removeProduct($id)
    {
        if (array_key_exists($id, self::$products)) {
            unset(self::$products[$id]);
        }
    }
}

class Product
{

    /**
     * @var integer|string
     */
    protected $id;


    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * @return integer|string
     */
    public function getId()
    {
        return $this->id;
    }
}

/*
 * =====================================
 *         USING OF OBJECT POOL
 * =====================================
 */

Factory::pushProduct(new Product('first'));
Factory::pushProduct(new Product('second'));

print_r(Factory::getProduct('first')->getId());
// first
echo '<br />';
print_r(Factory::getProduct('second')->getId()).'<br />';
// second
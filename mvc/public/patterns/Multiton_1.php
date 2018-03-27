
<?php
/**
 * Общий интерфейс пула одиночек
 */
abstract class FactoryAbstract
{

    /**
     * @var array
     */
    protected static $instances = array();


    /**
     * Возвращает экземпляр класса, из которого вызван
     *
     * @return static
     */
    public static function getInstance()
    {
        $className = static::getClassName();
        if (!(self::$instances[$className] instanceof $className)) {
            self::$instances[$className] = new $className();
        }
        return self::$instances[$className];
    }

    /**
     * Удаляет экземпляр класса, из которого вызван
     *
     * @return void
     */
    public static function removeInstance()
    {
        $className = static::getClassName();
        if (array_key_exists($className, self::$instances)) {
            unset(self::$instances[$className]);
        }
    }

    /**
     * Возвращает имя экземпляра класса
     *
     * @return string
     */
    final protected static function getClassName()
    {
        return get_called_class();
    }

    /**
     * Конструктор закрыт
     */
    protected function __construct()
    {
    }

    /**
     * Клонирование запрещено
     */
    final protected function __clone()
    {
    }

    /**
     * Сериализация запрещена
     */
    final protected function __sleep()
    {
    }

    /**
     * Десериализация запрещена
     */
    final protected function __wakeup()
    {
    }
}

/**
 * Интерфейс пула одиночек
 */
abstract class Factory extends FactoryAbstract
{

    /**
     * Возвращает экземпляр класса, из которого вызван
     *
     * @return static
     */
    final public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * Удаляет экземпляр класса, из которого вызван
     *
     * @return void
     */
    final public static function removeInstance()
    {
        parent::removeInstance();
    }
}

/*
 * =====================================
 *           USING OF MULTITON
 * =====================================
 */

/**
 * Первый одиночка
 */
class FirstProduct extends Factory
{
    public $a = [];
}

/**
 * Второй одиночка
 */
class SecondProduct extends FirstProduct
{
}

// Заполняем свойства одиночек
FirstProduct::getInstance()->a[] = 1;
SecondProduct::getInstance()->a[] = 2;
FirstProduct::getInstance()->a[] = 3;
SecondProduct::getInstance()->a[] = 4;

print_r(FirstProduct::getInstance()->a);
// array(1, 3)
echo '<br />';
print_r(SecondProduct::getInstance()->a);
// array(2, 4)
echo '<br />';
<?php
#declare(strict_types=1);

error_reporting(E_ERROR);
ini_set('display_errors', true);

// Базовая корзина покупок, содержащая список добавленных
// продуктов и количество каждого продукта. Включает метод,
// вычисляющий общую цену элементов корзины с помощью
// callback-замыкания.
class Cart
{
    protected $products = array();
    private $total_list = [];

    public function add($product, float $price, int $quantity)
    {
        if (isset($this->products[$product])) {
            if ($this->products[$product]['price'] == $price) {
                $this->products[$product]['quantity'] += $quantity;
            } else {
                die('You try add product "'.$product.'" with other price!<br />Normal price: '.$this->products[$product]['price'].'<br />Your price: '.$price);
            }

        } else {
            $this->products[$product]['name'] = $product;
            $this->products[$product]['quantity'] = $quantity;
            $this->products[$product]['price'] = $price;
        }
    }

    public function getQuantity($product)
    {
        return isset($this->products[$product]) ?? $this->products[$product];
    }

    public function getTotal($tax) : float
    {
        $total = 0.00;

        $callback =
            function ($product) use ($tax, &$total)
            {
                $pricePerItem = $product['price'];
                $total_price = ($pricePerItem * $product['quantity']) * ($tax + 1.0);
                $total += $total_price;
                $this->total_list[] = $product['name'].': '.round($total_price, 2).' руб.';
            };

        array_walk($this->products, $callback);
        return round($total, 2);
    }

    public function getTotalList()
    {
        print implode('<br />', $this->total_list);
    }
}

$cart = new Cart;

// Добавляем несколько элементов в корзину
$cart->add('butter', 54.00, 1);
$cart->add('milk', 49.00, 2);
$cart->add('eggs', 78.99, 1);
$cart->add('eggs', 78.99, 1);

// Выводим общую сумму с 5% налогом на продажу.
echo '<b>Total:</b> '.$cart->getTotal(0.18).' руб.';
echo '<br /><br />';
echo '<b>Products:</b><br />';
$cart->getTotalList();
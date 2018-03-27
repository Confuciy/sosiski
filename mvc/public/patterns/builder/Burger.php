<?php
class Burger
{
    protected $size;

    protected $cheese = false;
    protected $pepperoni = false;
    protected $lettuce = false;
    protected $tomato = false;

    public function __construct(BurgerBuilder $builder)
    {
        $this->size = $builder->size;
        $this->cheese = $builder->cheese;
        $this->pepperoni = $builder->pepperoni;
        $this->lettuce = $builder->lettuce;
        $this->tomato = $builder->tomato;
    }

    public function getBurgerInfo()
    {
        $ingridients = [];
        $vars = get_class_vars(__CLASS__);
        foreach ($vars as $var => $value) {
            if ($this->{$var} == true and $var != 'size') {
                $ingridients[] = $var;
            }
        }

        return ['size' => $this->size, 'ingridients' => $ingridients];
    }
}
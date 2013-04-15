<?php namespace Moltin\Cart;

class Item
{
    protected $id;
    protected $name;
    protected $quantity;

    public function __construct(array $item)
    {
        foreach ($item as $key => $value) $this->$key = $value;
    }

    public function __get($param)
    {
        return $this->$param;
    }
}
<?php namespace Moltin\Cart\Storage;

use Moltin\Cart\Item;

class Runtime implements \Moltin\Cart\StorageInterface
{
    protected $identifier;
    protected static $cart = array();

    public function insertUpdate(Item $item)
    {
        static::$cart[$this->id][$item->identifier] = $item;
    }

    public function data()
    {
        return static::$cart[$this->id];
    }

    public function has($id)
    {
        foreach ($this->data as $item) {

            if ($item['id'] == $id) return true;

        }
    }
    
    public function remove($id)
    {
        unset(static::$cart[$this->id][$id]);
    }

    public function destroy()
    {
        unset(static::$cart[$this->id]);
    }

    public function setIdentifier($id)
    {
        $this->id = $id;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }
}
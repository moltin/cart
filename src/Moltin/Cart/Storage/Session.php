<?php namespace Moltin\Cart\Storage;

use Moltin\Cart\Item;

class Session implements \Moltin\Cart\StorageInterface
{
    protected $identifier;
    protected static $cart = array();

    public function __construct()
    {
        session_id() or session_start();

        if (isset($_SESSION['cart'])) static::$cart = unserialize($_SESSION['cart']);
    }

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
        foreach (static::$cart[$this->id] as $item) {

            if ($item->id == $id) return true;

        }
    }

    public function item($id)
    {
        foreach ($this->data as $item) {

            if ($item->id == $id) return $item;

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

        if ( ! array_key_exists($this->id, static::$cart)) {
            static::$cart[$this->id] = array();
        }
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function __destruct()
    {
        $_SESSION['cart'] = serialize(static::$cart);
    }
}
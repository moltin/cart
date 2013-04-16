<?php namespace Moltin\Cart\Storage;

use Moltin\Cart\Item;

class Session implements \Moltin\Cart\StorageInterface
{
    protected $identifier;
    protected $cart = array();

    public function __construct()
    {
        session_id() or session_start();

        if (isset($_SESSION['cart'])) $this->cart = unserialize($_SESSION['cart']);
    }

    public function insertUpdate(Item $item)
    {
        $this->cart[$this->id][$item->identifier] = $item;
    }

    public function data()
    {
        return $this->cart[$this->id];
    }
    
    public function remove($id)
    {
        unset($this->cart[$this->id][$id]);
    }

    public function destroy()
    {
        unset($this->cart[$this->id]);
    }

    public function setIdentifier($id)
    {
        $this->id = $id;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function __destruct()
    {
        $_SESSION['cart'] = serialize($this->cart);
    }
}
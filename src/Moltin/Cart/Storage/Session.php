<?php namespace Moltin\Cart\Storage;

class Session implements \Moltin\Cart\StorageInterface
{
    protected $identifier;
    protected $cart = array();

    public function __construct()
    {
        session_id() or session_start();
    }

    public function insertUpdate($id, $data)
    {
        $this->cart[$id] = $data;
    }

    public function data()
    {

    }
    
    public function remove($id)
    {

    }

    public function destroy()
    {
        unset($_SESSION['cart'][$this->id]);
    }

    public function setIdentifier($id)
    {
        $this->id = $id;

        if (isset($_SESSION['cart'][$this->id])) {
            $this->cart = unserialize($_SESSION['cart'][$this->id]);
        }
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function __destruct()
    {
        $_SESSION['cart'][$this->id] = serialize($this->cart);
    }
}
<?php namespace Moltin\Cart\Storage;

class Session implements \Moltin\Cart\StorageInterface
{
    protected $identifier;

    public function __construct()
    {
        session_id() or session_start();
    }

    public function insertUpdate($id, $data)
    {
        
    }
    
    public function remove($id)
    {

    }

    public function destroy()
    {
        unset($_SESSION['cart']);
    }
    
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }
    
    public function getIdentifier($identifier)
    {
        return $this->identifier;
    }
}
<?php namespace Moltin\Cart;

interface StorageInterface
{
    public function insertUpdate($id, $data);
    
    public function remove($id);
    
    public function setIdentifier($identifier);
    
    public function getIdentifier($identifier);
}
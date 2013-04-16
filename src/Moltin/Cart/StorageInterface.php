<?php namespace Moltin\Cart;

interface StorageInterface
{
    public function insertUpdate(Item $item);

    public function data();

    public function has($id);
    
    public function remove($id);

    public function destroy();
    
    public function setIdentifier($identifier);
    
    public function getIdentifier();
}
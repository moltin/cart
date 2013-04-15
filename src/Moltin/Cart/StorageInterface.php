<?php namespace Moltin\Cart;

interface StorageInterface
{
    public function insertUpdate($id, $data);

    public function data();
    
    public function remove($id);

    public function destroy();
}
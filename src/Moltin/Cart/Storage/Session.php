<?php namespace Moltin\Cart\Storage;

use Moltin\Cart\Item;

class Session extends Runtime implements \Moltin\Cart\StorageInterface
{
    public function __construct()
    {
        session_id() or session_start();

        if (isset($_SESSION['cart'])) static::$cart = unserialize($_SESSION['cart']);
    }

    public function __destruct()
    {
        $_SESSION['cart'] = serialize(static::$cart);
    }
}

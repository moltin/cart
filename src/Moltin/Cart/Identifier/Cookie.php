<?php namespace Moltin\Cart\Identifier;

class Cookie implements \Moltin\Cart\IdentifierInterface
{
    public function get()
    {
        if (isset($_COOKIE['cart_identifier'])) return $_COOKIE['cart_identifier'];

        return $this->regenerate();
    }

    public function regenerate()
    {
        $identifier = md5(uniqid(null, true));

        setcookie('cart_identifier', $identifier);

        return $identifier;
    }
}
<?php namespace Moltin\Cart\Identifier;

class Cookie implements \Moltin\Cart\IdentifierInterface
{
    /**
     * Get the current or new unique identifier
     * 
     * @return string The identifier
     */
    public function get()
    {
        if (isset($_COOKIE['cart_identifier'])) return $_COOKIE['cart_identifier'];

        return $this->regenerate();
    }

    /**
     * Regenerate the identifier
     * 
     * @return string The identifier
     */
    public function regenerate()
    {
        $identifier = md5(uniqid(null, true));

        setcookie('cart_identifier', $identifier);

        return $identifier;
    }

    /**
     * Forget the identifier
     * 
     * @return void
     */
    public function forget()
    {
        return setcookie('cart_identifier', null, time()-3600);
    }
}
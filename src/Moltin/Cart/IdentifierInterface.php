<?php namespace Moltin\Cart;

interface IdentifierInterface
{
    /**
     * Get the current or new unique identifier
     * 
     * @return string The identifier
     */
    public function get();

    /**
     * Regenerate the identifier
     * 
     * @return string The identifier
     */
    public function regenerate();

    /**
     * Forget the identifier
     * 
     * @return void
     */
    public function forget();
}
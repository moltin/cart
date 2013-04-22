<?php namespace Moltin\Cart;

interface StorageInterface
{
    /**
     * Add or update an item in the cart
     * 
     * @param  Item   $item The item to insert or update
     * @return void
     */
    public function insertUpdate(Item $item);

    /**
     * Retrieve the cart data
     * 
     * @return array
     */
    public function &data();

    /**
     * Check if the item exists in the cart
     * 
     * @param  mixed  $id
     * @return boolean
     */
    public function has($id);

    /**
     * Get a single cart item by id
     * 
     * @param  mixed $id The item id
     * @return Item  The item class
     */
    public function item($id);

    /**
     * Remove an item from the cart
     * 
     * @param  mixed $id
     * @return void
     */
    public function remove($id);

    /**
     * Destroy the cart
     * 
     * @return void
     */
    public function destroy();
    
    /**
     * Set the cart identifier
     * 
     * @param string $identifier
     */
    public function setIdentifier($identifier);
    
    /**
     * Return the current cart identifier
     * 
     * @return void
     */
    public function getIdentifier();
}
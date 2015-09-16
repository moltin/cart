<?php

/**
 * This file is part of Moltin Cart, a PHP package to handle
 * your shopping basket.
 *
 * Copyright (c) 2013 Moltin Ltd.
 * http://github.com/moltin/cart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package moltin/cart
 * @author Chris Harvey <chris@molt.in>
 * @copyright 2013 Moltin Ltd.
 * @version dev
 * @link http://github.com/moltin/cart
 *
 */

namespace Moltin\Cart\Storage;

use Moltin\Cart\Item;

class Runtime implements \Moltin\Cart\StorageInterface
{
    protected $id;
    protected static $cart = array();

    /**
     * Add or update an item in the cart
     * 
     * @param  Item   $item The item to insert or update
     * @return void
     */
    public function insertUpdate(Item $item)
    {
        static::$cart[$this->id][$item->identifier] = $item;
    }

    /**
     * Retrieve the cart data
     * 
     * @return array
     */
    public function &data($asArray = false)
    {
        $cart =& static::$cart[$this->id];

        if ( ! $asArray) return $cart;

        $data = $cart;

        foreach ($data as &$item) {
            $item = $item->toArray();
        }

        return $data;
    }

    /**
     * Check if the item exists in the cart
     * 
     * @param  mixed  $id
     * @return boolean
     */
    public function has($identifier)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->identifier == $identifier) return true;

        }

        return false;
    }

    /**
     * Get a single cart item by id
     * 
     * @param  mixed $id The item id
     * @return Item  The item class
     */
    public function item($identifier)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->identifier == $identifier) return $item;

        }

        return false;
    }

    /**
     * Returns the first occurance of an item with a given id
     * 
     * @param  string $id The item id
     * @return Item       Item object
     */
    public function find($id)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->id == $id) return $item;

        }

        return false;
    }
    
    /**
     * Remove an item from the cart
     * 
     * @param  mixed $id
     * @return void
     */
    public function remove($id)
    {
        unset(static::$cart[$this->id][$id]);
    }

    /**
     * Destroy the cart
     * 
     * @return void
     */
    public function destroy()
    {
        static::$cart[$this->id] = array();
    }

    /**
     * Set the cart identifier
     * 
     * @param string $identifier
     */
    public function setIdentifier($id)
    {
        $this->id = $id;

        if ( ! array_key_exists($this->id, static::$cart)) {
            static::$cart[$this->id] = array();
        }
    }

    /**
     * Return the current cart identifier
     * 
     * @return void
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Save current state of the cart (in case of persistent storage)
     * 
     * @return void
     */
    public function save()
    {
        
    }
}

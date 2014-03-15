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

namespace Moltin\Cart;

use Moltin\Tax\Tax;
use InvalidArgumentException;

class Item
{
    protected $identifier;
    protected $store;
    protected $tax;

    protected $data = array();

    /**
     * Construct the item
     * 
     * @param string           $identifier
     * @param array            $item
     * @param StorageInterface $store
     */
    public function __construct($identifier, array $item, StorageInterface $store)
    {
        $this->identifier = $identifier;
        $this->store = $store;

        foreach ($item as $key => $value) $this->data[$key] = $value;

        $item['tax'] = isset($item['tax']) ? $item['tax'] : 0;

        $this->tax = new Tax($item['tax']);
    }

    /**
     * Return the value of protected methods
     * 
     * @param  any $param
     * @return mixed
     */
    public function __get($param)
    {
        if ($param == 'identifier') return $this->identifier;

        return array_key_exists($param, $this->data) ? $this->data[$param] : null;
    }

    /**
     * Update data array using set magic method
     * 
     * @param string $param The key to set
     * @param mixed $value The value to set $param to
     */
    public function __set($param, $value)
    {
        $this->data[$param] = $value;
    }

    /**
     * Removes the current item from the cart
     * 
     * @return void
     */
    public function remove()
    {
        $this->store->remove($this->identifier);
    }

    /**
     * Return the total tax for this item
     * 
     * @param boolean $single Tax for single item or all?
     * @return float
     */
    public function tax($single = false)
    {
        $quantity = $single ? 1 : $this->quantity;

        return $this->tax->rate($this->price*$quantity);
    }

    /**
     * Return the total of the item, with or without tax
     * 
     * @param  boolean $includeTax Whether or not to include tax
     * @return float              The total, as a float
     */
    public function total($includeTax = true)
    {
        $price = $this->price;

        if ($includeTax) $price = $this->tax->add($price);

        return (float)($price * $this->quantity);
    }

    /**
     * Update a single key for this item, or multiple
     * 
     * @param  array|string  $key The array key to update, or an array of key-value pairs to update
     * @return void
     */
    public function update($key, $value = null)
    {
        if (is_array($key)) {

            foreach ($key as $updateKey => $updateValue) {
                $this->update($updateKey, $updateValue);
            }

        } else {

            if ($key == 'quantity' and $value < 1) {
                return $this->remove();
            }
            
            if ($key == 'tax' and is_numeric($value)) {
                $this->tax = new Tax($value);
            }

            // Update the item
            $this->data[$key] = $value;

        }
    }
    
    /**
     * Check if this item has options
     * 
     * @return boolean Yes or no?
     */
    public function hasOptions()
    {
        return array_key_exists('options', $this->data) and ! empty($this->data['options']);
    }

    /**
     * Convert the item into an array
     * 
     * @return array The item data
     */
    public function toArray()
    {
        return $this->data;
    }
}

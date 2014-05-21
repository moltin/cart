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

use InvalidArgumentException;
use Moltin\Currency\Currency;

class Cart
{
    protected $id;
    
    protected $identifier;
    protected $store;
    
    protected $currency;

    protected $requiredParams = array(
        'id',
        'name',
        'quantity',
        'price'
    );

    /**
     * Cart constructor
     * 
     * @param StorageInterface    $store      The interface for storing the cart data
     * @param IdentifierInterface $identifier The interface for storing the identifier
     */
    public function __construct(StorageInterface $store, IdentifierInterface $identifier)
    {
        $this->store = $store;
        $this->identifier = $identifier;

        // Generate/retrieve identifier
        $this->id = $this->identifier->get();

        // Restore the cart from a saved version
        if (method_exists($this->store, 'restore')) $this->store->restore($this->id);

        // Let our storage class know which cart we're talking about
        $this->store->setIdentifier($this->id);
    }

    /**
     * Retrieve the cart contents
     * 
     * @return array An array of Item objects
     */
    public function &contents($asArray = false)
    {
        return $this->store->data($asArray);
    }

    /**
     * Retrieve the cart contents as an array
     * 
     * @return array An array of items
     */
    public function &contentsArray()
    {
        return $this->store->data(true);
    }

    /**
     * Insert an item into the cart
     * 
     * @param  array  $item An array of item data
     * @return string       A unique item identifier
     */
    public function insert(array $item)
    {
        $this->checkArgs($item);

        $itemIdentifier = $this->createItemIdentifier($item);

        if ($this->has($itemIdentifier)) {
            $item['quantity'] = $this->item($itemIdentifier)->quantity + $item['quantity'];
            $this->update($itemIdentifier, $item);

            return $itemIdentifier;
        }

        if ($item['quantity'] < 1) throw new InvalidArgumentException('Quantity can not be less than 1');

        $item = $this->createItem($itemIdentifier, $item);

        $this->store->insertUpdate($item);

        return $itemIdentifier;
    }

    /**
     * Update an item
     * 
     * @param  string $itemIdentifier The unique item identifier
     * @param  string|int|array $key  The key to update, or an array of key-value pairs
     * @param  mixed $value           The value to set $key to
     * @return void
     */
    public function update($itemIdentifier, $key, $value = null)
    {
        foreach ($this->contents() as $item) {

            if ($item->identifier == $itemIdentifier) {
                $item->update($key, $value);
                break;
            }

        }
    }

    /**
     * Remove an item from the cart
     * 
     * @param  string $identifier Unique item identifier
     * @return void
     */
    public function remove($identifier)
    {
        $this->store->remove($identifier);
    }

    /**
     * Destroy/empty the cart
     * 
     * @return void
     */
    public function destroy()
    {
        $this->store->destroy();
    }

    /**
     * Check if the cart has a specific item
     * 
     * @param  string  $itemIdentifier The unique item identifier
     * @return boolean                 Yes or no?
     */
    public function has($itemIdentifier)
    {
        return $this->store->has($itemIdentifier);
    }

    /**
     * Return a specific item object by identifier
     * 
     * @param  string $itemIdentifier The unique item identifier
     * @return Item                   Item object
     */
    public function item($itemIdentifier)
    {
        return $this->store->item($itemIdentifier);
    }

    /**
     * Returns the first occurance of an item with a given id
     * 
     * @param  string $id The item id
     * @return Item       Item object
     */
    public function find($id)
    {
        return $this->store->find($id);
    }

    /**
     * The total tax value for the cart
     * 
     * @return float The total tax value
     */
    public function tax()
    {
        $total = 0;

        foreach ($this->contents() as $item) $total += (float)$item->tax();

        return $total;
    }

    /**
     * The total value of the cart
     * 
     * @param  boolean $includeTax Include tax on the total?
     * @return float               The total cart value
     */
    public function total($includeTax = true)
    {
        $total = 0;

        foreach ($this->contents() as $item) $total += (float)$item->total($includeTax);

        return (float)$total;
    }

    /**
     * The total value of the cart with tax
     * 
     * @return float The total cart value
     */
    public function totalWithTax()
    {
        return $this->total(true);
    }

    /**
     * The total value of the cart without tax
     * 
     * @return float The total cart value
     */
    public function totalWithoutTax()
    {
        return $this->total(false);
    }

    /**
     * The total number of items in the cart
     * 
     * @param  boolean $unique Just return unique items?
     * @return int             Total number of items
     */
    public function totalItems($unique = false)
    {
        $total = 0;

        foreach ($this->contents() as $item) {
            $total += $unique ? 1 : $item->quantity;
        }

        return $total;
    }

    /**
     * The total number of unique items in the cart
     * 
     * @return int             Total number of items
     */
    public function totalUniqueItems()
    {
        return $this->totalItems(true);
    }

    /**
     * Set the currency object
     * 
     * @param \Moltin\Currency\Currency $currency The currency object
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the currency object
     * 
     * @return Currency The currency object for this cart
     */
    public function currency()
    {
        return $this->currency;
    }
    
    /**
     * Set the cart identifier, useful if restoring a saved cart
     * 
     * @param  mixed The identifier
     * @return void
     */
    public function setIdentifier($identifier)
    {
        $this->store->setIdentifier($identifier);
    }

    /**
     * Create a unique item identifier
     * 
     * @param  array  $item An array of item data
     * @return string       An md5 hash of item
     */
    protected function createItemIdentifier(array $item)
    {
        if ( ! array_key_exists('options', $item)) $item['options'] = array();

        ksort($item['options']);

        return md5($item['id'].serialize($item['options']));
    }

    /**
     * Check if a cart item has the required parameters
     * 
     * @param  array  $item An array of item data
     * @return void
     */
    protected function checkArgs(array $item)
    {
        foreach ($this->requiredParams as $param) {

            if ( ! array_key_exists($param, $item)) {
                throw new InvalidArgumentException("The '{$param}' field is required");
            }

        }
    }

    /**
     * Create an item object
     *
     * @param string $identifier The item identifier
     * @param array  $data       The item data
     * @return Item
     */
    protected function createItem($identifier, array $data)
    {
        return new Item($identifier, $data, $this->store);
    }
}

<?php namespace Moltin\Cart;

use InvalidArgumentException;

class Cart
{
    protected $id;
    protected $store;
    protected $identifier;

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

        // Let our storage class know which cart we're talking about
        $this->store->setIdentifier($this->id);
    }

    /**
     * Retrieve the cart contents
     * 
     * @return array An array of Item objects
     */
    public function &contents()
    {
        return $this->store->data();
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

        $item = new Item($itemIdentifier, $item, $this->store);

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

    public function destroy()
    {
        $this->store->destroy();
    }

    public function has($itemIdentifier)
    {
        return $this->store->has($itemIdentifier);
    }

    public function item($itemIdentifier)
    {
        return $this->store->item($itemIdentifier);
    }

    public function tax()
    {
        $total = 0;

        foreach ($this->contents() as $item) $total += (float)$item->tax();

        return $total;
    }

    public function total($includeTax = true)
    {
        $total = 0;

        foreach ($this->contents() as $item) $total += (float)$item->total($includeTax);

        return (float)$total;
    }

    public function totalItems($unique = false)
    {
        $total = 0;

        foreach ($this->contents() as $item) {
            $total += $unique ? 1 : $item->quantity;
        }

        return $total;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    protected function createItemIdentifier(array $item)
    {
        if ( ! array_key_exists('options', $item)) $item['options'] = array();

        ksort($item['options']);

        return md5($item['id'].serialize($item['options']));
    }

    protected function checkArgs(array $item)
    {
        foreach ($this->requiredParams as $param) {

            if ( ! array_key_exists($param, $item)) {
                throw new InvalidArgumentException("The '{$param}' field is required");
            }

        }
    }
}

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
     * Set the currency object
     * 
     * @param \Moltin\Currency\Currency $currency The currency object
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
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

    protected function checkArgs(array $item)
    {
        foreach ($this->requiredParams as $param) {

            if ( ! array_key_exists($param, $item)) {
                throw new InvalidArgumentException("The '{$param}' field is required");
            }

        }
    }
}

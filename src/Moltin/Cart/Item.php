<?php namespace Moltin\Cart;

use Moltin\Tax\Tax;

class Item
{
    public $identifier;
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
        return $this->data[$param];
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
     * @return float
     */
    public function tax()
    {
        return $this->tax->rate($this->price);
    }

    public function total($includeTax = true)
    {
        $price = $this->price;

        if ($includeTax) $price = $this->tax->add($price);

        return (float)($price * $this->quantity);
    }

    /**
     * Update a single key for this item, or multiple
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

            // Update the item
            $this->data[$key] = $value;

        }
    }
}

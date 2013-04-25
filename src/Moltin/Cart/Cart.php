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

    public function __construct(StorageInterface $store, IdentifierInterface $identifier)
    {
        $this->store = $store;
        $this->identifier = $identifier;

        // Generate/retrieve identifier
        $this->id = $this->identifier->get();

        // Let our storage class know which cart we're talking about
        $this->store->setIdentifier($this->id);
    }

    public function &contents()
    {
        return $this->store->data();
    }

    public function insert(array $item)
    {
        $this->checkArgs($item);

        $id = $this->createItemIdentifier($item);

        if ($this->has($id)) {
            $item['quantity'] = $this->item($id)->quantity + $item['quantity'];
            $this->update($id, $item);

            return $id;
        }

        $item = new Item($id, $item, $this->store);

        $this->store->insertUpdate($item);

        return $id;
    }

    public function update($id, $key, $value = null)
    {
        foreach ($this->contents() as $item) {

            if ($item->id == $id or $item->identifier == $id) {
                $item->update($key, $value);
                break;
            }

        }
    }

    public function remove($identifier)
    {
        $this->store->remove($identifier);
    }

    public function destroy()
    {
        $this->store->destroy();
    }

    public function has($id)
    {
        return $this->store->has($id);
    }

    public function item($id)
    {
        return $this->store->item($id);
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

    public function totalItems()
    {
        return count($this->contents());
    }

    public function setCurrency()
    {
        
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
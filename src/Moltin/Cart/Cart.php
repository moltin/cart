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
        'quantity'
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

    public function contents()
    {
        
    }

    public function insert(array $item)
    {
        $this->checkArgs($item);


    }

    public function update()
    {
        
    }

    public function remove()
    {
        
    }

    public function destroy()
    {
        
    }

    public function has()
    {
        
    }

    public function total()
    {
        
    }

    public function setTax()
    {
        
    }

    public function setCurrency()
    {
        
    }

    protected function createItemIdentifier(array $item)
    {
        unset($item['quantity']);
        ksort($item);

        return md5(serialize($item));
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
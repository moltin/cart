<?php namespace Moltin\Cart;

class Cart
{
    protected $id;
    protected $store;
    protected $identifier;

    public function __construct(StorageInterface $store, IdentifierInterface $identifier)
    {
        $this->store = $store;
        $this->identifier = $identifier;

        // Generate/retrieve identifier
        $this->id = $this->identifier->get();

        // Let our storage class know which cart we're talking about
        $this->store->setIdentifier($this->id);
    }

    public function insert()
    {

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
}
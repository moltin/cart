<?php

use Moltin\Cart\Cart;

class CartTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testDependencyInjection()
    {
        $cart = new Cart(new Moltin\Cart\Storage\Runtime, $this->mockIdentifier());
    }

    public function testInsert()
    {
        $cart = new Cart(new Moltin\Cart\Storage\Runtime, $this->mockIdentifier());

        $actualId = $cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        $identifier = md5('foo'.serialize(array()));

        $this->assertEquals($identifier, $actualId);
    }

    public function testTotals()
    {
        $cart = new Cart(new Moltin\Cart\Storage\Runtime, $this->mockIdentifier());

        // Generate a random price and quantity
        $price = rand(20, 99999);
        $quantity = rand(1, 10);

        $cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => $price,
            'quantity' => $quantity
        ));

        // Test that the total is being calculated successfully
        $this->assertEquals($cart->total(), $price*$quantity);
    }

    protected function mockStorage()
    {
        $storage = Mockery::mock('Moltin\\Cart\\StorageInterface');
        $storage->shouldReceive('setIdentifier')->once()->andReturn(true);

        return $storage;
    }

    protected function mockIdentifier()
    {
        $identifier = Mockery::mock('Moltin\\Cart\\IdentifierInterface');
        $identifier->shouldReceive('get')->once()->andReturn('foo');

        return $identifier;
    }
}
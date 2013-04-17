<?php

use Moltin\Cart\Cart;

class CartTest extends \PHPUnit_Framework_TestCase
{
    public function testDependencyInjection()
    {
        $cart = new Cart(new Moltin\Cart\Storage\Runtime, new Moltin\Cart\Identifier\Runtime);
    }

    public function testInsert()
    {
        $cart = new Cart(new Moltin\Cart\Storage\Runtime, new Moltin\Cart\Identifier\Runtime);

        $actualId = $cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        $identifier = md5('foo'.serialize(array()));

        $this->assertEquals($identifier, $actualId);
    }

    public function testUpdate()
    {
        $cart = new Cart(new Moltin\Cart\Storage\Runtime, new Moltin\Cart\Identifier\Runtime);

        $actualId = $cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        $cart->update('foo', 'name', 'baz');

        $this->assertEquals($cart->item('foo')->name, 'baz');
    }

    public function testTotals()
    {
        $cart = new Cart(new Moltin\Cart\Storage\Runtime, new Moltin\Cart\Identifier\Runtime);

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
}
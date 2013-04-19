<?php

use Moltin\Cart\Cart;

class CartTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cart = new Cart(new Moltin\Cart\Storage\Runtime, new Moltin\Cart\Identifier\Runtime);
    }

    public function tearDown()
    {
        $this->cart->destroy();
    }

    public function testInsert()
    {
        $actualId = $cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        $identifier = md5('foo'.serialize(array()));

        $this->assertEquals($identifier, $actualId);
    }

    public function testInsertIncrements()
    {
        $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 150,
            'quantity' => 1
        ));

        $this->assertEquals($this->cart->total(), 150);

        $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 150,
            'quantity' => 1
        ));

        $this->assertEquals($this->cart->total(), 300);
    }

    public function testUpdate()
    {
        $actualId = $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        $this->cart->update('foo', 'name', 'baz');

        $this->assertEquals($this->cart->item('foo')->name, 'baz');
    }

    public function testTotals()
    {
        // Generate a random price and quantity
        $price = rand(20, 99999);
        $quantity = rand(1, 10);

        $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => $price,
            'quantity' => $quantity
        ));

        // Test that the total is being calculated successfully
        $this->assertEquals($this->cart->total(), $price*$quantity);
    }
}
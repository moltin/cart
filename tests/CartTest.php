<?php

use Moltin\Cart\Cart;
use Moltin\Cart\Storage\Runtime as RuntimeStore;
use Moltin\Cart\Identifier\Runtime as RuntimeIdentifier;

class CartTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cart = new Cart(new RuntimeStore, new RuntimeIdentifier);
    }

    public function tearDown()
    {
        $this->cart->destroy();
    }

    public function testInsert()
    {
        $actualId = $this->cart->insert(array(
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

        $this->cart->update($actualId, 'name', 'baz');

        $this->assertEquals($this->cart->item($actualId)->name, 'baz');
    }

    public function testMagicUpdate()
    {
        $actualId = $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        foreach ($this->cart->contents() as $item) {
            $item->name = 'baz';
        }

        $this->assertEquals($this->cart->item($actualId)->name, 'baz');
    }
    
    public function testOptions()
    {
        $actualId = $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1,
            'options' => array(
                'size' => 'L'
            )
        ));
        
        $this->assertTrue($this->cart->item($actualId)->hasOptions());
        $this->assertNotEmpty($this->cart->item($actualId)->option);
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

    public function testTotalItems()
    {
        $adding = rand(1, 200);

        for ($i = 1; $i <= $adding; $i++) {
            $this->cart->insert(array(
                'id' => uniqid(),
                'name' => 'bar',
                'price' => 100,
                'quantity' => 1
            ));
        }

        $this->assertEquals($this->cart->totalItems(), $adding);
    }

    public function testItemRemoval()
    {
        $actualId = $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        $identifier = md5('foo'.serialize(array()));

        $contents =& $this->cart->contents();

        $this->assertNotEmpty($contents);

        foreach ($contents as $item) $item->remove();

        $this->assertEmpty($contents);
    }

    public function testAlternateItemRemoval()
    {
        $actualId = $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        $identifier = md5('foo'.serialize(array()));

        $contents =& $this->cart->contents();

        $this->assertNotEmpty($contents);

        foreach ($contents as $identifier => $item) $this->cart->remove($identifier);

        $this->assertEmpty($contents);
    }

    public function testTax()
    {
        $this->cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1,
            'tax' => 20
        ));

        // Test that the tax is being calculated successfully
        $this->assertEquals($this->cart->total(), 120);

        // Test that the total method can also return the pre-tax price if false is passed
        $this->assertEquals($this->cart->total(false), 100);
    }
}

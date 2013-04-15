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
        $cart = new Cart($this->mockStorage(), $this->mockIdentifier());
    }

    public function testInsert()
    {
        $storage = $this->mockStorage();
        $storage->shouldReceive('insertUpdate')->once()->andReturn(true);

        $cart = new Cart($storage, $this->mockIdentifier());

        $actualId = $cart->insert(array(
            'id' => 'foo',
            'name' => 'bar',
            'price' => 100,
            'quantity' => 1
        ));

        $identifier = md5('foo'.serialize(array()));

        $this->assertEquals($identifier, $actualId);
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
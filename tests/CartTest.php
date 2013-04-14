<?php

use Moltin\Cart\Cart;

class CartTest extends \PHPUnit_Framework_TestCase
{
    public function testDependencyInjection()
    {
        $storage = Mockery::mock('Moltin\\Cart\\StorageInterface');
        $storage->shouldReceive('setIdentifier')->once()->andReturn(true);

        $identifier = Mockery::mock('Moltin\\Cart\\IdentifierInterface');
        $identifier->shouldReceive('get')->once()->andReturn('foo');

        $cart = new Cart($storage, $identifier);
    }
}
<?php namespace Moltin\Cart;

interface IdentifierInterface
{
    public function get();

    public function regenerate();
}
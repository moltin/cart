<?php namespace Moltin\Cart;

interface IdentifierInterface
{
    public function getIdentifier($id, $data);

    public function regenerate();
}
<?php namespace Moltin\Cart\Identifier;

class Runtime implements \Moltin\Cart\IdentifierInterface
{
    protected static $identifier;

    public function get()
    {
        if (isset(static::$identifier)) return static::$identifier;

        return $this->regenerate();
    }

    public function regenerate()
    {
        $identifier = md5(uniqid(null, true));

        static::$identifier = $identifier;

        return $identifier;
    }

    public function forget()
    {
        unset(static::$identifier);
    }
}
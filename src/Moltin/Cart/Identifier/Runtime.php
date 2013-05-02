<?php namespace Moltin\Cart\Identifier;

class Runtime implements \Moltin\Cart\IdentifierInterface
{
    protected static $identifier;

    /**
     * Get the current or new unique identifier
     * 
     * @return string The identifier
     */
    public function get()
    {
        if (isset(static::$identifier)) return static::$identifier;

        return $this->regenerate();
    }

    /**
     * Regenerate the identifier
     * 
     * @return string The identifier
     */
    public function regenerate()
    {
        $identifier = md5(uniqid(null, true));

        static::$identifier = $identifier;

        return $identifier;
    }

    /**
     * Forget the identifier
     * 
     * @return void
     */
    public function forget()
    {
        unset(static::$identifier);
    }
}
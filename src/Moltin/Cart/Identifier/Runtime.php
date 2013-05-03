<?php

/**
 * This file is part of Moltin Cart, a PHP package to handle
 * your shopping basket.
 *
 * Copyright (c) 2013 Moltin Ltd.
 * http://github.com/moltin/cart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package moltin/cart
 * @author Chris Harvey <chris@molt.in>
 * @copyright 2013 Moltin Ltd.
 * @version dev
 * @link http://github.com/moltin/cart
 *
 */

namespace Moltin\Cart\Identifier;

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
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

namespace Moltin\Cart\Storage;

use Moltin\Cart\Item;

class Session extends Runtime implements \Moltin\Cart\StorageInterface
{
    /**
     * The Session store constructor
     */
    public function restore()
    {
        session_id() or session_start();

        if (isset($_SESSION['cart'])) static::$cart = unserialize($_SESSION['cart']);
    }

    /**
     * The session store destructor.
     */
    public function __destruct()
    {
        $_SESSION['cart'] = serialize(static::$cart);
    }
}

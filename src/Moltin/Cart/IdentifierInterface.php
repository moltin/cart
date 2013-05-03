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

namespace Moltin\Cart;

interface IdentifierInterface
{
    /**
     * Get the current or new unique identifier
     * 
     * @return string The identifier
     */
    public function get();

    /**
     * Regenerate the identifier
     * 
     * @return string The identifier
     */
    public function regenerate();

    /**
     * Forget the identifier
     * 
     * @return void
     */
    public function forget();
}
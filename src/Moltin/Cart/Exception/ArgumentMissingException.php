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

namespace Moltin\Cart\Exception;

class ArgumentMissingException extends \InvalidArgumentException
{
    private $argument;

    public function __construct($message, $argument, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->argument = $argument;
    }

    public function getArgument()
    {
        return $this->argument;
    }
}
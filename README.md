# Shopping Cart Package

[![Build Status](https://secure.travis-ci.org/moltin/cart.png)](http://travis-ci.org/moltin/cart)

* [Website](http://molt.in)
* [License](https://github.com/moltin/cart/master/LICENSE)
* Version: dev

The Moltin shopping cart composer package makes it easy to implement a shopping basket into your application and
store the cart data using one of the numerous data stores provided. You can also inject your own data store if you
would like your cart data to be stored elsewhere.

## Usage
Below is a basic usage guide for this package.

### Instantiating the cart
Before you begin, you will need to know which storage and identifier method you are going to use. The identifier is
how you store which cart is for that user. So if you store your cart in the database, then you need a cookie (or some
other way of storing an identifier) so we can link the user to a stored cart.

In this example we're going to use the cookie identifier and session for storage.

```php
use Moltin\Cart\Cart;
use Moltin\Cart\Storage\Session;
use Moltin\Cart\Identifier\Cookie;

$cart = new Cart(new Session, new Cookie);
```

### Inserting items into the cart
Inserting an item into the cart is easy. The required keys are id, name, price and quantity, although you can pass
over any custom data that you like.
```php
$cart->insert(array(
    'id'       => 'foo',
    'name'     => 'bar',
    'price'    => 100,
    'quantity' => 1
));
```

### Setting the tax rate for an item
Another key you can pass to your insert method is 'tax'. This is a percentage which you would like to be added onto
the price of the item.

In the below example we will use 20% for the tax rate.

```php
<?php

$cart->insert(array(
    'id'       => 'foo',
    'name'     => 'bar',
    'price'    => 100,
    'quantity' => 1,
    'tax'      => 20
));
```

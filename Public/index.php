<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Notification\Email;
use App\PaymentGateway\Stripe\Transaction as StripeTransaction;
use App\PaymentGateway\Paddle\Transaction as PaddleTransaction;
use App\PaymentGateway\Paddle\CustomerProfile;

$email = new Email();
echo $email->send('alice@example.com') . PHP_EOL;

$stripe = new StripeTransaction();
echo $stripe->charge(99.99) . PHP_EOL;

$paddle = new PaddleTransaction();
echo $paddle->charge(49.99) . PHP_EOL;

$customer = new CustomerProfile('Bob');
echo $customer->getName() . PHP_EOL;
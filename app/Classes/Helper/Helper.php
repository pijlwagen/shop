<?php

namespace App\Classes\Helper;

class Helper
{
    public static function discountedPrice($product, $includingOptions = true)
    {
        $price = $product->price;
        $discount = $product->discounts->first();
        if ($includingOptions) {
            foreach ($product->options as $option) {
                $price += $option->increment ?? 0;
            }
        }
        if ($discount) {
            switch ($discount->type) {
                case 'free':
                    return 0;
                case 'fixed':
                    return $price - $discount->amount;
                case 'percentage':
                    return $price - ($product->price / 100 * $discount->amount);
            }
        } else {
            return $price;
        }
    }

    public static function paymentMethod($string)
    {
        switch ($string) {
            case 'ideal':
                return 'iDeal';
            case 'applepay':
                return 'Apple Pay';
            case 'creditcard':
                return 'Credit Card';
            case 'paypal':
                return 'PayPal';
        }
    }
}

<?php

namespace App\Classes\Cart;

use App\Classes\Cart\Item;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class Cart
{
    public static function add($product, $qty = 1, $options = [])
    {
        $item = new Item($product, $qty, $options);
        $cart = json_decode(Session::get('cart', '[]'));
        if (self::has($item->hash)) {
            $cart->{$item->hash}->quantity += intval($qty);
        } else {
            try {
                $cart->{$item->hash} = $item;
            } catch (\Exception $exception) {
                $cart[$item->hash] = $item;
            }
        }
        return $cart;
    }

    public static function has($hash)
    {
        $array = json_decode(Session::get('cart', '[]'));
        return array_key_exists($hash, $array);
    }

    public static function get($hash)
    {
        $index = json_decode(Session::get('cart', '[]'));
        return $index->{$hash} ?? null;
    }

    public static function all()
    {
        return json_decode(Session::get('cart', '[]'));
    }

    public static function subTotal()
    {
        $items = self::all();
        $total = 0;
        foreach ($items as $item) {
            $price = $item->price;
            if ($item->discount) {
                $price = self::itemDiscountPrice($item->hash);
            } else {
                foreach ($item->options as $option) {
                    $price += $option->increment ?? 0;
                }
            }
            $total += $price * $item->quantity;
        }
        return $total;
    }

    public static function itemDiscountPrice($hash, $includingOptions = true)
    {
        $item = self::get($hash);
        $discount = $item->discount;
        if ($includingOptions) {
            foreach ($item->options as $option) {
                $item->price += $option->increment ?? 0;
            }
        }
        if ($discount) {
            switch ($discount->type) {
                case 'free':
                    return 0;
                case 'fixed':
                    return $item->price - $discount->amount;
                case 'percentage':
                    return $item->price - ($item->price / 100 * $discount->amount);
            }
        } else {
            return $item->price;
        }
    }

    public static function refresh()
    {
        $items = json_decode(Session::get('cart', '[]'));
        $json = [];
        $errors = [];
        foreach ($items as $item) {
            $product = Product::with(['images', 'options' => function ($query) {
                return $query->with(['values']);
            }, 'discounts' => function ($query) {
                return $query->where('active_from', '<', Carbon::now()->format('Y-m-d H:i:s'))->where('active_until', '>', Carbon::now()->format('Y-m-d H:i:s'))->orderBy('active_from', 'ASC');
            }])->findOrFail($item->id);
            try {
                $options = [];

                foreach ($item->options as $option) {
                    $options[$option->id] = $option->value_id;
                }

                $row = new Item($product, $item->quantity, $options);

                $json[$row->hash] = $row;
            } catch (\Exception $exception) {
                array_push($errors, "<a href='" . route('products.view', $item->slug) . "'><b>{$item->name}</b></a> was removed from your cart because some of the options are no longer available.");
            }
        }
        if (count($errors) > 0) Session::now('warning', $errors);
        Session::put('cart', json_encode($json));
    }

    public static function update($newValues)
    {
        $items = json_decode(Session::get('cart', '[]'));
        $json = [];
        foreach ($newValues as $hash => $quantity) {
            if ($quantity > 0) {
                $entry = $items->{$hash};
                $entry->quantity = $quantity;
                array_push($json, $entry);
                $items->{$hash}->quanity = $quantity;
            }
        }
        Session::put('cart', json_encode($json));
    }

    public static function count()
    {
        $json = json_decode(Session::get('cart', '[]'));
        $array = !is_array($json) ? get_object_vars($json) : [];
        $array = array_map(function ($x) {
            return $x->quantity;
        }, $array);
        return array_sum($array);
    }
}

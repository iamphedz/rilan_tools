<?php

namespace App\Http\CustomClass;

use App\Product;

class Cart
{

    /**
     * Updates the cart items saved in session
     */
    public static function updateItem(array $item)
    {
        $product = Product::with(["brand", "category"])
            ->orderBy('created_at', 'desc')
            ->where('id', $item['product_id'])->get()->first();

        $_SESSION["cart"]["cart_items"][$item["product_id"]]["product_instance"] = $product;
        $_SESSION["cart"]["cart_items"][$item["product_id"]]["qty"] = $item["qty"];


        return  true;
    }

    /**
     * Returns cart items
     */

    public static function getAll()
    {
        if (isset($_SESSION["cart"]["cart_items"])) {
            if (count($_SESSION["cart"]["cart_items"]) > 0) {
                return $_SESSION["cart"]["cart_items"];
            }
        }
        return false;
    }

    /**
     * Returns the session id of the cart
     */
    public static function getId()
    {
        return $_SESSION["cart"]["session_id"];
    }

    /**
     * checks and return item if it exists in the cart session
     */

    public static function getOne($id)
    {
        if (isset($_SESSION["cart"]["cart_items"][$id]))
            return $_SESSION["cart"]["cart_items"][$id];

        return false;
    }

    /**
     * removes item from cart with given item id
     */
    public static function removeItem($id)
    {
        if (isset($_SESSION["cart"]["cart_items"][$id])) {
            unset($_SESSION["cart"]["cart_items"][$id]);
            return true;
        }

        return false;
    }

    /**
     * unset items from cart
     */
    public static function empty()
    {
        if (isset($_SESSION["cart"]["cart_items"])) {
            unset($_SESSION["cart"]["cart_items"]);
            return true;
        }
        return false;
    }

    public static function encodeData()
    {
        if ($cart_items = self::getAll()) {

            $products = collect($cart_items)->reduce(function ($products, $cart_item) {
                $products[$cart_item["product_instance"]->id] = [
                    'item_id'   =>  $cart_item["product_instance"]->id,
                    'item_name' =>  $cart_item["product_instance"]->product_name,
                    'qty'       =>  $cart_item["qty"],
                    'price'     =>  $cart_item["product_instance"]->price,
                    'total'     =>  $cart_item["product_instance"]->price * $cart_item["qty"]
                ];
                return $products;
            });

            return array(
                'session_id'    =>  self::getId(),
                'products'      =>  $products,
                'total'         =>  array(
                    'additional_fee'    =>  request()->get('additional_fee') ? request()->get('additional_fee') : "0.00",
                    'discount'          =>  request()->get("discount") ? request()->get("discount") : "0.00",
                    'sub_total'         =>  collect($products)->sum('total')
                )
            );
        }

        return false;
    }

    /**
     * sets up a cart session with session_id
     */
    public static function setCart()
    {
        if (isset($_SESSION["cart"])) {
            if (!isset($_SESSION["cart"]["session_id"])) {
                $_SESSION["cart"]["session_id"] = session_id();
            } else {
                if ($_SESSION["cart"]["session_id"] != session_id()) {
                    unset($_SESSION["cart"]);
                    self::newCart();
                }
            }
        } else {
            self::newCart();
        }

        return $_SESSION["cart"];
    }

    /**
     * creates a new cart session 
     */

    public static function newCart()
    {
        $_SESSION["cart"]["session_id"] = session_id();
    }
}

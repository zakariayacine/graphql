<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Item;

class ItemObserver
{
    /**
     * Handle the Item "created" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function created(Item $item)
    {
        $product = Product::find($item->product_id);
        $product->stock = $product->stock - $item->quantity;
        $item->total_price = $item->quantity * $product->price;
        $product->saveQuietly();
        $item->saveQuietly();
        $order = Order::find($item->order_id);
        $order->price += $item->total_price;
        $order->total_price += $item->total_price;
        $order->saveQuietly();
    }


    /**
     * Handle the Item "updated" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function updated(Item $item)
    {
        //delete old total price of item in his order
        $order = Order::find($item->order_id);
        $new_order_price =  $order->price - $item->total_price;
        $order->price = $new_order_price;
        $order->saveQuietly();
        //calculate new total price of an item and update it
        $product = Product::find($item->product_id);
        $new_total_price = $product->price * $item->quantity;
        $item->total_price = $new_total_price;
        $item->saveQuietly();

        //update order
        $items = Item::where('order_id', $item->order_id)->get();
        $total_price = 0;
        foreach ($items as $item) {
            $total_price += $item->total_price;
        }
        $order = Order::find($item->order_id);
        $order->price = $total_price;
        $order->total_price =  $order->delivery_price + $total_price;
        $order->saveQuietly();
    }

    /**
     * Handle the Item "deleted" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function deleted(Item $item)
    {
        $order_id = $item->order_id;
        $order = Order::find($order_id);
        $order->total_price = $order->total_price - $item->total_price;
        $order->price = $order->price - $item->total_price;
        $order->saveQuietly();
    }

    /**
     * Handle the Item "restored" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function restored(Item $item)
    {
        //
    }

    /**
     * Handle the Item "force deleted" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function forceDeleted(Item $item)
    {
        //
    }
}

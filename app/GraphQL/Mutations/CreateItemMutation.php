<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;
use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateItemMutation
{
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Shared between all fields.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */
    public function create($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $qtt = Arr::get($args, 'item.quantity');
        $orderid = Arr::get($args, 'item.Orderid');
        $product = $this->getProduct($args);
        $total = $this->getTotalPrice($qtt, $product->price);
        $item = $this->Item($qtt, $product->id, $total, $orderid);
        return $item;
    }

    public function getProduct($data)
    {
        $product_id = Arr::get($data, 'item.product_id');
        $product = Product::where('id', $product_id)->first();
        return $product;
    }

    public function getTotalPrice($qtt, $price)
    {
        $total = $qtt * $price;
        return $total;
    }

    public function Item($qtt, $product_id, $total, $orderid)
    {

        if ($this->ifItemExist($product_id, $orderid)) {
            $var = $this->updateItem($qtt, $product_id, $total, $orderid);
            return $var;
        }
        return $this->createItem($qtt, $product_id, $total, $orderid);
    }

    public function updateItem($qtt, $product_id, $total, $orderid)
    {
        $updateitem = Item::where('product_id', $product_id)->where('order_id', $orderid)->first();
        $updateitem->quantity = $qtt;
        $updateitem->product_id = $product_id;
        $updateitem->total_price = $total;
        $updateitem->save();
        $updateOrder = Order::find($orderid);
        $updateOrder->price = $this->totalCount($orderid);
        $updateOrder->delivery_price = 500;
        $updateOrder->total_price = $this->totalCount($orderid) + 500;
        $updateOrder->save();
        return $updateitem;
    }

    public function createItem($qtt, $product_id, $total, $orderid)
    {
        $item = new Item();
        $item->quantity = $qtt;
        $item->product_id = $product_id;
        $item->total_price = $total;
        $item->order_id = $orderid;
        $order = new Order();
        $order->id = $orderid;
        $order->price = $total;
        $order->delivery_price = 500;
        $order->total_price = $total + 500;
        $order->save();
        $item->save();
        return $item;
    }

    public function ifItemExist($product_id, $orderid)
    {
        $item = Item::where('product_id', $product_id)->where('order_id', $orderid)->first();
        return $item;
    }

    public function totalCount($orderid)
    {
        $totalPrice = 0;
        $items = Item::where('order_id', $orderid)->get();
        foreach ($items as $item) {
            $totalPrice += $item->total_price;
        }
        dump($totalPrice);
        return $totalPrice;
    }
}

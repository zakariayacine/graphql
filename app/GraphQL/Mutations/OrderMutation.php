<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\Item;
use App\Models\Order;


class OrderMutation
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
        $order = new Order();
        $order->price = 0;
        $order->delivery_price = Arr::get($args, 'items.deliveryPrice');
        $order->total_price  = Arr::get($args, 'items.deliveryPrice');
        $order->user_id = Arr::get($args, 'items.userId');
        $order->save();
        $items = Arr::get($args, 'items.cartProduct');
        foreach ($items as $item) {
            $data = new Item();
            $data->quantity = $item['quantity'];
            $data->product_id = $item['product_id'];
            $data->order_id = $order->id;
            $data->save();
        }
        return Order::where('id', $order->id)->first();
    }

    public function update($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $item_quantity = (Arr::get($args, 'item.quantity'));
        $item_id = (Arr::get($args, 'item.item_id'));
        $item = Item::find($item_id);
        $item->quantity = $item_quantity;
        $item->save();

        $order = Item::where('id', $item_id)->first();
        $order_id = $order->order_id;
        return Order::where('id', $order_id)->first();
    }
    public function delete($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $item_id = (Arr::get($args, 'item.item_id'));
        $id = Item::find($item_id);
        $order_id = $id->order_id;
        $id->delete();
        return Order::where('id', $order_id)->first();
    }
}

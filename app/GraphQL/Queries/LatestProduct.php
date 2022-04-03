<?php
namespace App\GraphQL\Queries;

use App\Models\Product;

class LatestProduct
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        return Product::all();
    }
}

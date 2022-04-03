<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Product;

class StockCheckRule implements Rule, DataAwareRule
{

    protected $data;

    public function setData($data)
    {
        $this->data = $data['items']['cartProduct'];
        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $product_id = $this->FunctionName($value, $this->data);
            if(Product::find($product_id)){
                if ($value <= Product::find($product_id)->stock) {
                    return true;
                }
            }
           
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Error Out of stock !';
    }

    public function FunctionName($val, $array)
    {
        foreach ($array as $element) {
            if ($element['quantity'] == $val) {
                return $element['productId'];
            }
        }
        return null;
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Item;

class ItemExiste implements Rule
{
    protected $order_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $item = Item::find($value);
        if ($item) {
         $this->order_id = $item->order_id;
            if (count(Item::where('order_id', $this->order_id)->get()) >= 2) {
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
        if (count(Item::where('order_id', $this->order_id)->get()) === 1) {
            return 'This item dont existe';
        }
        return 'You cant delete this item';
    }
}

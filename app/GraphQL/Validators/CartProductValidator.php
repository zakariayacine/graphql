<?php

namespace App\GraphQL\Validators;
use App\Rules\ProductExisteRule;
use App\Rules\StockCheckRule;
use Nuwave\Lighthouse\Validation\Validator;

class CartProductValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {

        return [
            'productId' => [
                'required',
                new ProductExisteRule,
            ],
            'quantity' => [
                'required',
                new StockCheckRule,
            ],

        ];
    }
}

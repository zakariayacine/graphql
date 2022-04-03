<?php

namespace App\GraphQL\Validators;

use App\Rules\ItemExiste;
use Nuwave\Lighthouse\Validation\Validator;

class CartItemDeleteValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'itemId' => [
                'required',
                new ItemExiste,
            ],
        ];
    }
}

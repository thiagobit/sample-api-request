<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'detail' => 'array|required',
            'detail.product_variant' => 'array|required',
            'detail.product_variant.id' => 'integer|required',
            'detail.product_variant.sku' => 'string|required|max:64',
            'detail.product_variant.created_at' => 'string',
            'detail.product_variant.updated_at' => 'string',
            'detail.product_variant.product' => 'array|required',
            'detail.product_variant.product.id' => 'integer',
            'detail.product_variant.product.title' => 'string|required|max:2048',
            'detail.product_variant.sourcing_category' => 'array|nullable',
            'detail.product_variant.sourcing_category.id' => 'integer',
            'detail.product_variant.sourcing_category.title' => 'string|max:64',
            'detail.product_variant.options' => 'array',
            'detail.product_variant.options.*.id' => 'string',
            'detail.product_variant.options.*.name' => 'string',
            'detail.product_variant.options.*.sku_value' => 'string',
            'detail.product_variant.options.*.attribute' => 'array',
            'detail.product_variant.options.*.attribute.id' => 'string',
            'detail.product_variant.options.*.attribute.name' => 'string',
            'detail.product_variant.options.*.attribute.title' => 'string',
            'detail.product_variant.options.*.attribute.attribute_type' => 'string',
            'detail.product_variant.images' => 'array|present',
            'detail.product_variant.images.*.id' => 'integer',
            'detail.product_variant.images.*.url' => 'string|required|max:2048',
        ];
    }
}

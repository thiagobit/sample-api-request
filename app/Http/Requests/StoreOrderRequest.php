<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'detail.order' => 'array|required',
            'detail.order.autostore_order_id' => 'string|required|max:64',
            'detail.order.status' => 'string|required|in:Validated,Stowed',
            'detail.order.created_at' => 'string|required',
            'detail.order.instructions' => 'array|nullable',
            'detail.order.instructions.*.type' => 'string|required|max:32',
            'detail.order.instructions.*.text' => 'string|required|max:2048',
            'detail.order.items' => 'array|required',
            'detail.order.items.*.id' => 'integer|required',
            'detail.order.items.*.sku' => 'string|nullable|max:64',
            'detail.order.items.*.barcode' => 'string|nullable|max:64',
            'detail.order.items.*.item_type' => 'string',
            'detail.order.items.*.fulfillment_type' => 'string|nullable',
            'detail.order.items.*.customized' => 'boolean|required',
            'detail.order.items.*.quantity' => 'numeric|required',
            'detail.order.items.*.images' => 'array|present',
            'detail.order.items.*.images.*.url' => 'string|required|max:2048',
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $product = Products::create($this->validated($request));

        return response()->json($product->load('tier:id,name'), 201);
    }

    public function update(Request $request, Products $product): JsonResponse
    {
        $product->update($this->validated($request, $product->id));

        return response()->json($product->load('tier:id,name'));
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'product_code' => ['required', 'string', 'max:255',
                Rule::unique('products', 'product_code')->ignore($ignoreId)],
            'name'     => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'price'    => ['nullable', 'numeric', 'min:0'],
            'tier_id'  => ['required', Rule::exists('tiers', 'id')],
        ]);
    }
}
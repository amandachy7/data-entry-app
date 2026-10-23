<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stores;
use App\Models\Tiers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $store = Stores::create($data);

        return response()->json($store->load('tier:id,name'), 201);
    }

    public function update(Request $request, Stores $store): JsonResponse
    {
        $data = $this->validated($request, $store->id);
        $store->update($data);

        return response()->json($store->load('tier:id,name'));
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
    return $request->validate([
        'store_code' => ['required', 'string', 'max:255',
            Rule::unique('stores', 'store_code')->ignore($ignoreId)],
        'name'     => ['required', 'string', 'max:255'],
        'address'  => ['required', 'string'],
        'city'     => ['required', 'string', 'max:255'],
        'province' => ['required', 'string', 'max:255'],
        'tier_id'  => ['required', Rule::exists('tiers', 'id')],
    ]);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entries;
use App\Models\Products;
use App\Models\Stores;
use App\Models\Tiers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    // GET /api/stores -> isi dropdown Store
   public function stores(): JsonResponse
    {
        $stores = Stores::with('tier:id,name')
            ->orderBy('name')
            ->get();

        return response()->json($stores);
    }

    // GET /api/stores/{store}/products -> isi dropdown Product sesuai tier store
    public function productsByStore(Stores $store): JsonResponse
    {
        $products = Products::where('tier_id', $store->tier_id)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        return response()->json($products);
    }

    // GET /api/entries -> isi tabel data
  public function index(): JsonResponse
{
    $entries = Entries::with([
            'store:id,name,tier_id',
            'store.tier:id,name',
            'product:id,name',
        ])
        ->latest()
        ->get();

    return response()->json($entries);
}

    // POST /api/entries -> simpan form
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'store_id'   => ['required', 'exists:stores,id'],
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $store   = Stores::findOrFail($validated['store_id']);
        $product = Products::findOrFail($validated['product_id']);

        if ($product->tier_id !== $store->tier_id) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => [
                    'product_id' => ['Produk tidak sesuai dengan tier store yang dipilih.'],
                ],
            ], 422);
        }

        $entry = Entries::create($validated);
        $entry->load(['store:id,name', 'product:id,name']);

        return response()->json($entry, 201);
    }

    public function products(): JsonResponse
{
    $products = Products::with('tier:id,name')
        ->orderBy('name')
        ->get();

    return response()->json($products);
}
}
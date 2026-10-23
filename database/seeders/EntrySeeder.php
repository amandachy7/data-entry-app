<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrySeeder extends Seeder
{
    public function run(): void
    {
        $storeId = DB::table('stores')->where('id', 1)->value('id');
        $productId = DB::table('products')->where('id', 1)->value('id');

        if ($storeId && $productId) {
            DB::table('entries')->updateOrInsert(
                ['name' => 'Amanda', 'store_id' => $storeId, 'product_id' => $productId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}

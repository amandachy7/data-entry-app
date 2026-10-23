<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = DB::table('tiers')->pluck('id', 'name');

        $products = [
            ['product_code' => 'PRD-001', 'name' => 'Artisanal Country Sourdough Loaf', 'category' => 'Artisanal Bread', 'price' => 55000, 'tier' => 'Tier A'],
            ['product_code' => 'PRD-002', 'name' => 'Valrhona Dark Chocolate Babka', 'category' => 'Viennoiserie', 'price' => 68000, 'tier' => 'Tier A'],
            ['product_code' => 'PRD-003', 'name' => 'Truffle & Smoked Beef Croissant', 'category' => 'Savory Pastry', 'price' => 48000, 'tier' => 'Tier A'],
            ['product_code' => 'PRD-004', 'name' => 'Signature Basque Burnt Cheesecake Slice', 'category' => 'Cakes & Tarts', 'price' => 52000, 'tier' => 'Tier A'],
            ['product_code' => 'PRD-005', 'name' => 'French Butter Croissant', 'category' => 'Viennoiserie', 'price' => 28000, 'tier' => 'Tier B'],
            ['product_code' => 'PRD-006', 'name' => 'Pain au Chocolat', 'category' => 'Viennoiserie', 'price' => 32000, 'tier' => 'Tier B'],
            ['product_code' => 'PRD-007', 'name' => 'Japanese Milk Bread Shokupan', 'category' => 'Loaves & Toast', 'price' => 35000, 'tier' => 'Tier B'],
            ['product_code' => 'PRD-008', 'name' => 'Smoked Chicken & Cheese Danish', 'category' => 'Savory Pastry', 'price' => 30000, 'tier' => 'Tier B'],
            ['product_code' => 'PRD-009', 'name' => 'Pistachio Cardamom Escargot', 'category' => 'Viennoiserie', 'price' => 42000, 'tier' => 'Tier A'],
            ['product_code' => 'PRD-010', 'name' => 'Classic Cinnamon Morning Roll', 'category' => 'Sweet Buns', 'price' => 22000, 'tier' => 'Tier B'],
            ['product_code' => 'PRD-011', 'name' => 'Roti Kopi Klasik (Coffee Bun)', 'category' => 'Sweet Buns', 'price' => 12000, 'tier' => 'Tier C'],
            ['product_code' => 'PRD-012', 'name' => 'Roti Cokelat Keju (Chocolate Cheese Bun)', 'category' => 'Sweet Buns', 'price' => 11000, 'tier' => 'Tier C'],
            ['product_code' => 'PRD-013', 'name' => 'Roti Abon Ayam Gurih (Chicken Floss Bun)', 'category' => 'Savory Buns', 'price' => 14000, 'tier' => 'Tier C'],
            ['product_code' => 'PRD-014', 'name' => 'Donat Kampung Gula Halus', 'category' => 'Donuts', 'price' => 8000, 'tier' => 'Tier C'],
            ['product_code' => 'PRD-015', 'name' => 'Banana Molen Danish', 'category' => 'Sweet Pastry', 'price' => 10000, 'tier' => 'Tier C'],
        ];

        foreach ($products as $product) {
            $tierName = $product['tier'];
            unset($product['tier']);

            DB::table('products')->updateOrInsert(
                ['product_code' => $product['product_code']],
                [
                    ...$product,
                    'tier_id' => $tiers[$tierName],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

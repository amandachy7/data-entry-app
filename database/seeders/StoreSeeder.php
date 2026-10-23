<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = DB::table('tiers')->pluck('id', 'name');

        $stores = [
            ['store_code' => 'STR-JKT-001', 'name' => 'Innocean Bakery Senopati', 'address' => 'Jl. Senopati No. 45, Kebayoran Baru', 'city' => 'Kota Administrasi Jakarta Selatan', 'province' => 'Daerah Khusus Ibukota Jakarta', 'tier' => 'Tier A'],
            ['store_code' => 'STR-JKT-002', 'name' => 'Innocean Bakery Kelapa Gading', 'address' => 'Jl. Boulevard Raya Blok LA-4', 'city' => 'Jakarta Utara', 'province' => 'DKI Jakarta', 'tier' => 'Tier B'],
            ['store_code' => 'STR-BDG-001', 'name' => 'Innocean Bakery Riau Heritage', 'address' => 'Jl. LLRE Martadinata No. 88', 'city' => 'Bandung', 'province' => 'Jawa Barat', 'tier' => 'Tier A'],
            ['store_code' => 'STR-BKS-001', 'name' => 'Innocean Bakery Summarecon', 'address' => 'Jl. Bulevar Ahmad Yani Blok M', 'city' => 'Bekasi', 'province' => 'Jawa Barat', 'tier' => 'Tier C'],
            ['store_code' => 'STR-SBY-001', 'name' => 'Innocean Bakery Darmo', 'address' => 'Jl. Raya Darmo No. 112, Wonokromo', 'city' => 'Surabaya', 'province' => 'Jawa Timur', 'tier' => 'Tier A'],
            ['store_code' => 'STR-MLG-001', 'name' => 'Innocean Bakery Ijen', 'address' => 'Jl. Besar Ijen No. 24, Klojen', 'city' => 'Malang', 'province' => 'Jawa Timur', 'tier' => 'Tier B'],
            ['store_code' => 'STR-DPS-001', 'name' => 'Innocean Bakery Seminyak', 'address' => 'Jl. Kayu Aya No. 18X, Seminyak', 'city' => 'Badung', 'province' => 'Bali', 'tier' => 'Tier A'],
            ['store_code' => 'STR-UBU-001', 'name' => 'Innocean Bakery Ubud Hub', 'address' => 'Jl. Raya Ubud No. 35, Ubud', 'city' => 'Gianyar', 'province' => 'Bali', 'tier' => 'Tier B'],
            ['store_code' => 'STR-MDN-001', 'name' => 'Innocean Bakery Merdeka', 'address' => 'Jl. Balai Kota No. 7, Kesawan', 'city' => 'Medan', 'province' => 'Sumatera Utara', 'tier' => 'Tier B'],
            ['store_code' => 'STR-MKS-001', 'name' => 'Innocean Bakery Losari', 'address' => 'Jl. Penghibur No. 52, Ujung Pandang', 'city' => 'Makassar', 'province' => 'Sulawesi Selatan', 'tier' => 'Tier C'],
        ];

        foreach ($stores as $store) {
            $tierName = $store['tier'];
            unset($store['tier']);

            DB::table('stores')->updateOrInsert(
                ['store_code' => $store['store_code']],
                [
                    ...$store,
                    'tier_id' => $tiers[$tierName],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KasiraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Primary Kasira User: Reski Setiawan
        $user = User::firstOrCreate(
            ['email' => 'reski@lifehub.id'],
            [
                'name' => 'Reski Setiawan',
                'username' => 'reski',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Business: Warung Suka Maju
        $business = Business::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Warung Suka Maju'],
            [
                'business_type' => 'Warung',
                'phone' => '081234567890',
                'address' => 'Jl. Tebet Raya No. 24, Jakarta Selatan',
                'low_stock_threshold' => 5,
                'receipt_footer' => 'Terima kasih telah berbelanja di Warung Suka Maju!',
                'currency' => 'IDR',
            ]
        );

        // 3. Categories
        $categoriesData = [
            'Makanan' => 'makanan',
            'Minuman' => 'minuman',
            'Snack' => 'snack',
            'Rokok' => 'rokok',
            'Sembako' => 'sembako',
            'Lainnya' => 'lainnya',
        ];

        $categories = [];
        foreach ($categoriesData as $catName => $catSlug) {
            $categories[$catSlug] = Category::firstOrCreate(
                ['business_id' => $business->id, 'slug' => $catSlug],
                ['name' => $catName]
            );
        }

        // 4. Products matching reference
        $productsSeed = [
            [
                'name' => 'Indomie Goreng',
                'barcode' => '8998866200225',
                'category_id' => $categories['makanan']->id,
                'cost_price' => 2800,
                'selling_price' => 3500,
                'stock' => 20,
                'min_stock' => 5,
                'description' => 'Indomie Mi Goreng Instan 85gr',
            ],
            [
                'name' => 'Teh Botol Sosro',
                'barcode' => '8992775211118',
                'category_id' => $categories['minuman']->id,
                'cost_price' => 3000,
                'selling_price' => 4000,
                'stock' => 15,
                'min_stock' => 5,
                'description' => 'Teh Botol Sosro Original 450ml',
            ],
            [
                'name' => 'Chitato',
                'barcode' => '8993175537554',
                'category_id' => $categories['snack']->id,
                'cost_price' => 9500,
                'selling_price' => 12000,
                'stock' => 3, // Low stock
                'min_stock' => 5,
                'description' => 'Chitato Rasa Sapi Panggang 68gr',
            ],
            [
                'name' => 'Aqua 600ml',
                'barcode' => '8886008101053',
                'category_id' => $categories['minuman']->id,
                'cost_price' => 2000,
                'selling_price' => 3000,
                'stock' => 25,
                'min_stock' => 5,
                'description' => 'Air Mineral Aqua Botol 600ml',
            ],
            [
                'name' => 'Kopi Kapal Api',
                'barcode' => '8991002101018',
                'category_id' => $categories['minuman']->id,
                'cost_price' => 7500,
                'selling_price' => 10000,
                'stock' => 2, // Low stock
                'min_stock' => 4,
                'description' => 'Kopi Kapal Api Special Mix 10s',
            ],
            [
                'name' => 'Taro Net',
                'barcode' => '8993175537600',
                'category_id' => $categories['snack']->id,
                'cost_price' => 4800,
                'selling_price' => 6000,
                'stock' => 10,
                'min_stock' => 3,
                'description' => 'Taro Net Seaweed 65gr',
            ],
            [
                'name' => 'Susu Ultra',
                'barcode' => '8992741911019',
                'category_id' => $categories['minuman']->id,
                'cost_price' => 4000,
                'selling_price' => 5000,
                'stock' => 18,
                'min_stock' => 5,
                'description' => 'Ultra Milk UHT Full Cream 250ml',
            ],
            [
                'name' => 'Mie Sedaap',
                'barcode' => '8998866200331',
                'category_id' => $categories['makanan']->id,
                'cost_price' => 2800,
                'selling_price' => 3500,
                'stock' => 14,
                'min_stock' => 5,
                'description' => 'Mie Sedaap Goreng Original 90gr',
            ],
            [
                'name' => 'Beng-Beng',
                'barcode' => '8991001102029',
                'category_id' => $categories['snack']->id,
                'cost_price' => 1800,
                'selling_price' => 2500,
                'stock' => 20,
                'min_stock' => 5,
                'description' => 'Wafer Cokelat Karamel Beng-Beng',
            ],
            [
                'name' => 'Roti Sari Roti',
                'barcode' => '8992753200101',
                'category_id' => $categories['makanan']->id,
                'cost_price' => 5500,
                'selling_price' => 7000,
                'stock' => 2, // Low stock
                'min_stock' => 5,
                'description' => 'Sari Roti Sobek Cokelat Keju',
            ],
        ];

        $createdProducts = [];
        foreach ($productsSeed as $p) {
            $createdProducts[$p['name']] = Product::updateOrCreate(
                ['business_id' => $business->id, 'name' => $p['name']],
                $p
            );
        }

        // 5. Seed Transactions for Past Days & Today
        if ($business->transactions()->count() === 0) {
            $today = Carbon::today();

            // Daily totals for the 7-day chart curve (e.g. 180K, 320K, 410K, 360K, 290K, 280K, 482K today)
            $pastTotals = [
                6 => 190000,
                5 => 310000,
                4 => 420000,
                3 => 350000,
                2 => 285000,
                1 => 275000,
            ];

            foreach ($pastTotals as $daysAgo => $totalTarget) {
                $trxDate = $today->copy()->subDays($daysAgo)->setHour(14);
                $trx = $business->transactions()->create([
                    'user_id' => $user->id,
                    'invoice_number' => 'TRX-'.$trxDate->format('Ymd').'-001',
                    'total_amount' => $totalTarget,
                    'paid_amount' => $totalTarget,
                    'change_amount' => 0,
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'created_at' => $trxDate,
                    'updated_at' => $trxDate,
                ]);

                $trx->items()->create([
                    'product_id' => $createdProducts['Indomie Goreng']->id,
                    'product_name' => 'Indomie Goreng',
                    'price' => 3500,
                    'cost_price' => 2800,
                    'quantity' => 10,
                    'subtotal' => 35000,
                    'created_at' => $trxDate,
                    'updated_at' => $trxDate,
                ]);
            }

            // Today's 18 transactions totaling Rp 482.000 with 42 items sold
            $todayTransactions = [
                ['inv' => '#TRX-0018', 'time' => '10:24', 'total' => 18500, 'items' => [['Indomie Goreng', 3], ['Teh Botol Sosro', 2]]],
                ['inv' => '#TRX-0017', 'time' => '10:15', 'total' => 27000, 'items' => [['Chitato', 2], ['Aqua 600ml', 1]]],
                ['inv' => '#TRX-0016', 'time' => '10:02', 'total' => 12000, 'items' => [['Chitato', 1]]],
                ['inv' => '#TRX-0015', 'time' => '09:47', 'total' => 32500, 'items' => [['Kopi Kapal Api', 3], ['Beng-Beng', 1]]],
                ['inv' => '#TRX-0014', 'time' => '09:32', 'total' => 9000, 'items' => [['Aqua 600ml', 3]]],
                ['inv' => '#TRX-0013', 'time' => '09:15', 'total' => 38000, 'items' => [['Indomie Goreng', 4], ['Teh Botol Sosro', 3], ['Susu Ultra', 2]]],
                ['inv' => '#TRX-0012', 'time' => '09:00', 'total' => 24500, 'items' => [['Indomie Goreng', 2], ['Beng-Beng', 3], ['Aqua 600ml', 2]]],
                ['inv' => '#TRX-0011', 'time' => '08:45', 'total' => 29000, 'items' => [['Taro Net', 2], ['Susu Ultra', 2], ['Mie Sedaap', 2]]],
                ['inv' => '#TRX-0010', 'time' => '08:30', 'total' => 35000, 'items' => [['Chitato', 2], ['Teh Botol Sosro', 2]]],
                ['inv' => '#TRX-0009', 'time' => '08:15', 'total' => 22000, 'items' => [['Roti Sari Roti', 2], ['Teh Botol Sosro', 2]]],
                ['inv' => '#TRX-0008', 'time' => '08:00', 'total' => 45000, 'items' => [['Indomie Goreng', 3], ['Chitato', 2], ['Kopi Kapal Api', 1]]],
                ['inv' => '#TRX-0007', 'time' => '07:45', 'total' => 19500, 'items' => [['Mie Sedaap', 2], ['Beng-Beng', 2], ['Teh Botol Sosro', 1]]],
                ['inv' => '#TRX-0006', 'time' => '07:30', 'total' => 31000, 'items' => [['Chitato', 1], ['Kopi Kapal Api', 1], ['Susu Ultra', 1]]],
                ['inv' => '#TRX-0005', 'time' => '07:15', 'total' => 25000, 'items' => [['Beng-Beng', 4], ['Aqua 600ml', 2], ['Taro Net', 1]]],
                ['inv' => '#TRX-0004', 'time' => '07:00', 'total' => 28000, 'items' => [['Roti Sari Roti', 2], ['Roti Sari Roti', 2]]],
                ['inv' => '#TRX-0003', 'time' => '06:45', 'total' => 37000, 'items' => [['Indomie Goreng', 2], ['Chitato', 2], ['Aqua 600ml', 2]]],
                ['inv' => '#TRX-0002', 'time' => '06:30', 'total' => 33000, 'items' => [['Taro Net', 2], ['Susu Ultra', 3], ['Beng-Beng', 2]]],
                ['inv' => '#TRX-0001', 'time' => '06:15', 'total' => 45000, 'items' => [['Chitato', 2], ['Kopi Kapal Api', 2]]],
            ];

            // Normalize sum to exactly 482.000
            $todaySum = array_sum(array_column($todayTransactions, 'total'));
            $adjustment = 482000 - $todaySum;
            $todayTransactions[0]['total'] += $adjustment;

            foreach ($todayTransactions as $tData) {
                [$hour, $minute] = explode(':', $tData['time']);
                $createdAt = $today->copy()->setHour((int) $hour)->setMinute((int) $minute);

                $trx = $business->transactions()->create([
                    'user_id' => $user->id,
                    'invoice_number' => $tData['inv'],
                    'total_amount' => $tData['total'],
                    'paid_amount' => $tData['total'] + 5000,
                    'change_amount' => 5000,
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($tData['items'] as $it) {
                    $prodName = $it[0];
                    $qty = $it[1];
                    $prod = $createdProducts[$prodName] ?? null;

                    if ($prod) {
                        $subtotal = $prod->selling_price * $qty;
                        $trx->items()->create([
                            'product_id' => $prod->id,
                            'product_name' => $prod->name,
                            'price' => $prod->selling_price,
                            'cost_price' => $prod->cost_price,
                            'quantity' => $qty,
                            'subtotal' => $subtotal,
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ]);
                    }
                }
            }
        }

        // 6. Separate user for data isolation testing
        $otherUser = User::firstOrCreate(
            ['email' => 'budi@kasira.id'],
            [
                'name' => 'Budi Santoso',
                'username' => 'budisantoso',
                'password' => Hash::make('password'),
            ]
        );

        $otherBusiness = Business::firstOrCreate(
            ['user_id' => $otherUser->id, 'name' => 'Kedai Kopi Budi'],
            [
                'business_type' => 'Cafe / Kedai Kopi',
                'phone' => '085566778899',
                'address' => 'Jl. Senopati No. 10',
                'low_stock_threshold' => 3,
                'currency' => 'IDR',
            ]
        );

        $kopiCat = Category::firstOrCreate(
            ['business_id' => $otherBusiness->id, 'slug' => 'kopi-spesial'],
            ['name' => 'Kopi Spesial']
        );

        Product::firstOrCreate(
            ['business_id' => $otherBusiness->id, 'name' => 'Espresso Double Shot'],
            [
                'barcode' => '7778889990001',
                'category_id' => $kopiCat->id,
                'cost_price' => 8000,
                'selling_price' => 22000,
                'stock' => 50,
                'min_stock' => 10,
                'description' => 'Espresso blend Arabica Gayo',
            ]
        );
    }
}

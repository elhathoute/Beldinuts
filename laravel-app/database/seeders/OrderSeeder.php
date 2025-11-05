<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::where('role', 'client')->get();
        $products = \App\Models\Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Create 20 random orders
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $orderItems = [];
            $total = 0;

            // Add 1-4 random products to each order
            $selectedProducts = $products->random(rand(1, 4));

            foreach ($selectedProducts as $product) {
                $quantity = rand(50, 500); // 50g to 500g
                $subtotal = $product->price_per_gram * $quantity;
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity_grams' => $quantity,
                    'unit_price' => $product->price_per_gram,
                ];
            }

            // Ensure minimum order of 100 DH
            if ($total < 100) {
                $product = $products->random();
                $additionalQuantity = ceil((100 - $total) / $product->price_per_gram);
                $subtotal = $product->price_per_gram * $additionalQuantity;
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity_grams' => $additionalQuantity,
                    'unit_price' => $product->price_per_gram,
                ];
            }

            $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => $statuses[array_rand($statuses)],
                'tracking' => 'TRK' . strtoupper(\Illuminate\Support\Str::random(10)),
                'phone' => $user->phone ?? fake()->phoneNumber(),
                'address' => $user->address ?? fake()->address(),
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Create some reviews
            if (rand(0, 1)) {
                \App\Models\Review::create([
                    'user_id' => $user->id,
                    'product_id' => $selectedProducts->first()->id,
                    'rating' => rand(4, 5),
                    'comment' => fake()->sentence(),
                ]);
            }
        }
    }
}

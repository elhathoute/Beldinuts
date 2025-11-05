<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Graines noires',
                'description' => 'Graines noires naturelles et savoureuses, riches en nutriments essentiels.',
                'price_per_gram' => 2.5,
                'retail_price' => 2.5,
                'wholesale_price' => 2.0,
                'weight_per_piece' => 20,
                'unit' => 'piece',
                'stock' => 5000,
                'image' => 'https://www.shutterstock.com/image-photo/sunflower-seed-isolated-on-white-600nw-2458579765.jpg',
            ],
            [
                'name' => 'Cacao normal',
                'description' => 'Cacao de qualité supérieure, naturel et riche en antioxydants.',
                'price_per_gram' => 2.5,
                'retail_price' => 2.5,
                'wholesale_price' => 2.0,
                'weight_per_piece' => 40,
                'unit' => 'piece',
                'stock' => 3000,
                'image' => 'https://i.ytimg.com/vi/d7qetuMtgAc/maxresdefault.jpg',
            ],
            [
                'name' => 'Cacao croquant',
                'description' => 'Fèves de cacao croquantes, parfaites pour une collation délicieuse et croquante.',
                'price_per_gram' => 2.0,
                'retail_price' => 2.0,
                'wholesale_price' => 1.8,
                'weight_per_piece' => 40,
                'unit' => 'piece',
                'stock' => 3500,
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfcq8vM9bF_LmLGLermWnYww1mVfVg7k1xIw&s',
            ],
            [
                'name' => 'Pois chiches',
                'description' => 'Pois chiches secs, riches en protéines végétales et fibres. Parfaits pour vos recettes.',
                'price_per_gram' => 2.5,
                'retail_price' => 2.5,
                'wholesale_price' => 2.0,
                'weight_per_piece' => 40,
                'unit' => 'piece',
                'stock' => 6000,
                'image' => 'https://www.goji.ma/cdn/shop/products/pois-chiche-vrac.png?v=1717869605',
            ],
            [
                'name' => 'Graines blanches',
                'description' => 'Graines blanches naturelles, excellentes pour la santé et la nutrition.',
                'price_per_gram' => 2.0,
                'retail_price' => 2.0,
                'wholesale_price' => 1.8,
                'weight_per_piece' => 40,
                'unit' => 'piece',
                'stock' => 4500,
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0wDFjfmWkbsXwUw8qhipHDn1LE56yKgBpNw&s',
            ],
            [
                'name' => 'Amandes',
                'description' => 'Amandes premium du Maroc, riches en protéines et vitamines E. Parfaites pour une collation saine.',
                'price_per_gram' => 6.0,
                'retail_price' => 6.0,
                'wholesale_price' => 5.0,
                'weight_per_piece' => 35,
                'unit' => 'piece',
                'stock' => 4000,
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ3L7oGhxWbpwalFyid9U_mWWzpKGIRotWrNg&s',
            ],
            [
                'name' => 'Pistaches',
                'description' => 'Pistaches grillées naturellement, croquantes et savoureuses. Un délice pour l\'apéritif.',
                'price_per_gram' => 7.0,
                'retail_price' => 7.0,
                'wholesale_price' => 6.0,
                'weight_per_piece' => 36,
                'unit' => 'piece',
                'stock' => 2500,
                'image' => 'https://observatoire-des-aliments.fr/wp-content/uploads/2021/11/pistaches-696x393.jpg',
            ],
            [
                'name' => 'Noix de cajou',
                'description' => 'Noix de cajou grillées à sec, sans huile ajoutée. Croquantes et délicieuses.',
                'price_per_gram' => 7.0,
                'retail_price' => 7.0,
                'wholesale_price' => 6.0,
                'weight_per_piece' => 35,
                'unit' => 'piece',
                'stock' => 3000,
                'image' => 'https://images.prismic.io/la-fourche/a2e39df0-fe56-4854-81c0-d98cb2622740_noix+de+cajou+.jpg?auto=compress,format',
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}

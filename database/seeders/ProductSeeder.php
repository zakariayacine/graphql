<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; ++$i) {
            DB::table('products')->insert([
                'name' => Str::random(10),
                'price' => rand(500,5000),
                'user_id' =>rand(1,10),
                'categorie_id' => rand(1,10),
                'stock' => rand(5,100),
                'slug' => Str::slug($faker->sentence,'-'),
            ]);
        }
    }
}

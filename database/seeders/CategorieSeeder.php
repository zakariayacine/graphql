<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; ++$i) {
            DB::table('categories')->insert([
                'name' => Str::random(10),
                'parent_id' => rand(1,10),
                'slug' => Str::slug($faker->sentence,'-'),
            ]);
        }
    }
}

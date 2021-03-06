<?php

use Illuminate\Database\Seeder;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $limit = 20;
        for ($i = 1; $i < $limit; $i++) {
            DB::table('custom_fields')->insert([
                'name' => $faker->name,
                'value' => $faker->name,
            ]);
        }
    }
}

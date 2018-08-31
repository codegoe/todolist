<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TodolistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(0,10) as $i){
            DB::table('todolists')->insert([
                'name' => $faker->name,
                'email' => $faker->email
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Meal::factory(10)->create();
        foreach(\App\Models\Meal::all() as $meal) {
            $tags = \App\Models\Tag::inRandomOrder()->take(rand(1,3))->pluck('id');
            $meal->tags()->attach($tags);
        }
    }
}

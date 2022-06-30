<?php

namespace Database\Seeders;

use Carbon\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meal;
use Illuminate\Support\Facades\DB;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(Meal::class,10)->create();
        for($i=0;$i<20;$i++){
            DB::table('meals')->insert([
                'title' => 'Title of Meal number - '. $i+1,
                'description' => 'Description of Meal number - '. $i+1,
                'category_id' => rand(1,20)
            ]);
        }

    }
}

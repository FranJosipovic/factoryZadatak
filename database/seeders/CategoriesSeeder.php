<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i=0;$i<20;$i++){
            DB::table('categories')->insert([
                'title' => 'Title of Category number - '. $i+1,
                'slug' => 'Category - '. $i+1,
            ]);
        }
    }
}

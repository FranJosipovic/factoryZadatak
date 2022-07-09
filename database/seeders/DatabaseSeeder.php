<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meal;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
               
            for($i=0;$i<20;$i++){
                $id = $i +1 ;
                foreach(['en','hr','de','fr'] as $lang){
                    if($lang == 'en'){
                    DB::table('meal_translations')->insert([
                        'locale' => $lang,
                        'title' => "English translation of title, meal id - {$id}",
                        'description' => "English translation of description, meal id - {$id}",
                        'meal_id' => $id
                    ]); 
                    }
                    if($lang == 'de'){
                        DB::table('meal_translations')->insert([
                            'locale' => $lang,
                            'title' => "Deutsche Übersetzung von Titel, Mahlzeit-id - {$id}",
                            'description' => "Deutsche Ubersetzung von Beschreibung, Mahlzeit-id - {$id}",
                            'meal_id' => $id
                        ]); 
                        }
                    if($lang == 'hr'){
                        DB::table('meal_translations')->insert([
                            'locale' => $lang,
                            'title' => "hrvatski prijevod naslova, id jela - {$id}",
                            'description' => "hrvatski prijevod opisa, id jela - {$id}",
                            'meal_id' => $id
                        ]); 
                    }
                    if($lang == 'fr'){
                        DB::table('meal_translations')->insert([
                            'locale' => $lang,
                            'title' => "Traduction française du titre, plat ID - {$id}",
                            'description' => "Traduction française de la description, plat ID - {$id}",
                            'meal_id' => $id
                        ]); 
                    }    
            }
        }
    }
}

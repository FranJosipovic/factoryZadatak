<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meal;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $meals = Meal::all();
        foreach($meals as $meal){
            $meal->fill([
                'en' => [
                    'title' => "English translation of title, meal id - {$meal->id}",
                    'description' => "English translation of description, meal id - {$meal->id}"
                  ],
                'de' => [
                    'title' => "Deutsche Übersetzung von Titel, Mahlzeit-id - {$meal->id}",
                    'description' => "Deutsche Ubersetzung von Beschreibung, Mahlzeit-id - {$meal->id}"
                ],
                'hr' => [
                    'title' => "hrvatski prijevod naslova, id jela - {$meal->id}",
                    'description' => "hrvatski prijevod opisa, id jela - {$meal->id}"
                ]
                ]);
        }
    }
}

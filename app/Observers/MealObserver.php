<?php

namespace App\Observers;

use App\Models\Meal;

class MealObserver
{

    public $afterCommit = true;
    /**
     * Handle the Meal "created" event.
     *
     * @param  \App\Models\Meal  $meal
     * @return void
     */
    public function created(Meal $meal)
    {
        $meal->status = "created";
        $meal->update();
    }

    /**
     * Handle the Meal "updated" event.
     *
     * @param  \App\Models\Meal  $meal
     * @return void
     */
    public function updated(Meal $meal)
    {
        $meal->status = "updated";
        $meal->update();
    }

    /**
     * Handle the Meal "deleted" event.
     *
     * @param  \App\Models\Meal  $meal
     * @return void
     */
    public function deleted(Meal $meal)
    {
        $meal->status = "deleted";
        $meal->update();
        
    }

    /**
     * Handle the Meal "restored" event.
     *
     * @param  \App\Models\Meal  $meal
     * @return void
     */
    public function restored(Meal $meal)
    {
        //
    }

    /**
     * Handle the Meal "force deleted" event.
     *
     * @param  \App\Models\Meal  $meal
     * @return void
     */
    public function forceDeleted(Meal $meal)
    {
        //
    }
}

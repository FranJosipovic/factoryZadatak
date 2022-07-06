<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    public function meals(){
        return $this->belongsToMany(Meal::class);
    }
    protected $hidden = ['created_at','deleted_at','updated_at','pivot'];
}

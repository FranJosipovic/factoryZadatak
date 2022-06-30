<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
    use HasFactory;
    public function ingredients(){
        return $this->belongsToMany(Ingredient::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }

}

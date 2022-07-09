<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Meal extends Model implements TranslatableContract
{
    use HasFactory,SoftDeletes;
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
    public function ingredients(){
        return $this->belongsToMany(Ingredient::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }

    protected $hidden = ['created_at','deleted_at','updated_at','category_id','translations'];

    use Translatable;
    
    public $translatedAttributes = ['title', 'description'];
}

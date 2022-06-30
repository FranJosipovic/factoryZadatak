<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Meal;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Ingredient;
use Mockery\Undefined;

class MealController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function show(){

        $lang = request('lang');
        $with = request('with');
        $tags = request('tags');

        
        if($tags){
            $tags = explode(',',$tags);
            $tagWithMeals = Tag::with('meals')->whereIn('id',$tags)->get()->first();
            $mealsArray = $tagWithMeals->meals;
            if(in_array('tags',explode(',',$with)) && in_array('ingredients',explode(',',$with)) && in_array('category',explode(',',$with))){
                $meals = [];
                foreach($mealsArray as $meal){
                    $meals[] = Meal::where('id',$meal->id)->with(array('category','tags','ingredients'=>function ($query){
                        $query->select('id','title','slug');
                    }))->get();
                }
                /*pokušaj : with(array(explode(',',$with)=>function ($query){ --> omogućilo bi jednostavnost...
                        $query->select('id','title','slug');
                    }))
                    
                kada koristim Meal::select('id','title','description')->where('id',$meal->id)->with(array('category','tags','ingredients'=>function ($query){
                        $query->select('id','title','slug');
                    }))->get();
                ne dobivam rezultate za category i tags

                whereIn('id',$tags) ako je više tagova nisam uspio daobiti rezultate one koje sadrzavaju obavezno oba taga

                nisam uspio saznat kako da izbacim custom query rezultat na osnovu diff_timea, deleted_at... dio sa status : kreiran modificiran obrisan
                */
                return $meals;
            }else if(in_array('tags',explode(',',$with)) && in_array('ingredients',explode(',',$with))){
                $meals = [];
                foreach($mealsArray as $meal){
                    $meals[] = Meal::where('id',$meal->id)->with(array('tags','ingredients'=>function ($query){
                        $query->select('id','title','slug');
                    }))->get();
                }
                return $meals;
            }else if(in_array('tags',explode(',',$with)) && in_array('category',explode(',',$with))){
                $meals = [];
                foreach($mealsArray as $meal){
                    $meals[] = Meal::where('id',$meal->id)->with(array('category','tags'=>function ($query){
                        $query->select('id','title','slug');
                    }))->get();
                }
                return $meals;
            }else if(in_array('ingredients',explode(',',$with)) && in_array('category',explode(',',$with))){
                $meals = [];
                foreach($mealsArray as $meal){
                    $meals[] = Meal::where('id',$meal->id)->with(array('category','ingredients'=>function ($query){
                        $query->select('id','title','slug');
                    }))->get();
                }
                return $meals;
            }else{
                $meals = [];
                foreach($mealsArray as $meal){
                    $meals[] = Meal::where('id',$meal->id)->get();
                }
                return $meals;
            }
        }else{
            $mealsArray = [];
        }

        if($category = request('category')){
            if(in_array('tags',explode(',',$with)) && in_array('ingredients',explode(',',$with)) && in_array('category',explode(',',$with))){
                $arrayByCategory = Meal::where('category_id',$category)->with(array('category','tags','ingredients'=>function ($query){
                    $query->select('id','title','slug');
                }))->get()->toArray();
            }else if(in_array('tags',explode(',',$with)) && in_array('ingredients',explode(',',$with))){
                $arrayByCategory = Meal::where('category_id',$category)->with(array('tags','ingredients'=>function ($query){
                    $query->select('id','title','slug');
                }))->get()->toArray();
            }else if(in_array('tags',explode(',',$with)) && in_array('category',explode(',',$with))){
                $arrayByCategory = Meal::where('category_id',$category)->with(array('category','tags'=>function ($query){
                    $query->select('id','title','slug');
                }))->get()->toArray();
            }else if(in_array('ingredients',explode(',',$with)) && in_array('category',explode(',',$with))){
                $arrayByCategory = Meal::where('category_id',$category)->with(array('category','ingredients'=>function ($query){
                    $query->select('id','title','slug');
                }))->get()->toArray();
            }else{
                $arrayByCategory = Meal::where('category_id',$category)->get()->toArray();
            }
        }else{
            $arrayByCategory = [];
        }
        $meals = array_merge($mealsArray,$arrayByCategory);

        $totalItems = count($mealsArray) + count($arrayByCategory);

        if($items_per_page = request('per_page') && $currentPage = request('page')){
            $totalPages = $items_per_page > $totalItems ? 1 : $totalItems%$items_per_page + floor($totalItems/$items_per_page);

            $prevPage = $currentPage - 1;
            $prev = $prevPage == 0 ? null : "per_page=2&tags=10&lang=hr&with=ingredients,category,tags&page= {$prevPage}" ; 

            $nextPage = $currentPage + 1 ;
            $next = $nextPage > $totalPages ? null : "per_page=2&tags=10&lang=hr&with=ingredients,category,tags&page= {$nextPage}" ; 

            $self = $_SERVER['QUERY_STRING'];
        }else{
            $totalPages = 1;
            $currentPage = 1;
            $self = $_SERVER['QUERY_STRING'];
            $prev = null;
            $next = null;
            $items_per_page = $totalItems;
        }        

        return json_encode(['meta'=>['currentPage'=>$currentPage,'totalItems'=>$totalItems,'totalPages'=>$totalPages,'itemsPerPage'=>$items_per_page],'data'=>[$meals],'links'=>['prev'=>$prev,'self'=>$self,'next'=>$next],]);
        
    }
}

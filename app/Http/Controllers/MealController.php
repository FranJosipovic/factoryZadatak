<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Meal;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Ingredient;
use Mockery\Undefined;
use Validator;

class MealController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function show(Request $req){

        /*
            --koliko sam shvatio treba za validaciju postaviti da j elang required, ako treba još nešta dodao bi to u $rules
            --napravio sam observer i napravio potrebne promjene u EventServiceProvider, zbog linije koda u boot funkciji ne radi program pa sam ga zakomentiro i zbog
            toga sam manualno updato status u tablici u bazi podataka na 'deleted' za one koji su obrisani
            --ako nema with parametra javlja gresku pa sam morao dodati dodatne if else uvjete, vjerujem da se moglo drukcije i jednostavnije
        */

        $rules = array(
            "lang"=>"required"
        );

        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }

        $lang = request('lang');
        $with = request('with');
        $tags = request('tags');
        $category = request('category');
        $diff_time = request('diff_time');

        if($tags){
            $tagWithMeals = Tag::with('meals')->whereIn('id',[$tags])->get()->first();
            $mealsArray = $tagWithMeals->meals;
            if($with){
                foreach($mealsArray as $meal){
                    $meals[] = Meal::where('id',$meal->id)->with(explode(",",$with))->get();
                }
            }else{
                foreach($mealsArray as $meal){
                    $meals[] = Meal::where('id',$meal->id)->get();
                }
            }
        }
        
        if($category){
            if($with){
                $meals[] = Meal::where("category_id",$category)->with(explode(",",$with))->get();
            }else{
                $meals[] = Meal::where("category_id",$category)->get();
            }
        }
        
        if($diff_time){
            if($with){
                $meals [] = Meal::withTrashed()->where("deleted_at", ">",$diff_time)->orWhere("created_at",">",$diff_time)->orWhere("updated_at",">",$diff_time)->with(explode(",",$with))->get();
            }
            else{
                $meals [] = Meal::withTrashed()->where("deleted_at", ">",$diff_time)->orWhere("created_at",">",$diff_time)->orWhere("updated_at",">",$diff_time)->get();
            }
        }

        

        $totalItems = count($mealsArray);

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
        return ['meta'=>['currentPage'=>$currentPage,'totalItems'=>$totalItems,'totalPages'=>$totalPages,'itemsPerPage'=>$items_per_page],'data'=>$meals,'links'=>['prev'=>$prev,'self'=>$self,'next'=>$next]];
        
    }
}

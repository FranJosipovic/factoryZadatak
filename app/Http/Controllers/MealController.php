<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;
use Validator;
use Illuminanate\Pagination\Paginator;
use Illuminate\Contracts\Support\Jsonable;

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
            --pokušao sam i translatable, ali ne znam kako da seedam mealTranslations tablicu
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
        $items_per_page = request('per_page');

        app()->setLocale($lang);
        if($tags || $category){

            $meals = $with ? 
            Meal::whereHas('tags',function($query){
                $query->whereIn('id',[request('tags')]);
            })->orWhere("category_id",$category)->with(explode(",",$with))->get()
            :
            Meal::whereHas('tags',function($query){
                $query->whereIn('id',[request('tags')]);
            })->orWhere("category_id",$category)->get();
        }
        
        if($diff_time){

            $meals[] = $with ? 
            Meal::withTrashed()->where("deleted_at", ">",$diff_time)->orWhere("created_at",">",$diff_time)->orWhere("updated_at",">",$diff_time)->with(explode(",",$with))->get()
            :
            Meal::withTrashed()->where("deleted_at", ">",$diff_time)->orWhere("created_at",">",$diff_time)->orWhere("updated_at",">",$diff_time)->get();
        }        

        $totalItems = count($meals);
        if($items_per_page && $currentPage = request('page')){
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
        //return $meals;
        return ['meta'=>['currentPage'=>$currentPage,'totalItems'=>$totalItems,'totalPages'=>$totalPages,'itemsPerPage'=>$items_per_page],'data'=>$meals,'links'=>['prev'=>$prev,'self'=>$self,'next'=>$next]];
    }
}

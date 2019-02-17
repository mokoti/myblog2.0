<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$categories = new Category;

        // (1)filltering
        if( is_array($request->input('q')) ){
            
            foreach( $request->input('q') as $key => $value ){

                if($key !== 's'){

                    $pettern = '#([^\.]*)\.?([^\.]*)_([^\._]*)#u';
                    preg_match($pettern,$key,$m);

                    $collumn_name = $m[1];
                    $related_column_name = $m[2];

                    if($m[3] == 'eq'){
                        $operator = '=';
                    }elseif($m[3] == 'cont'){
                        $operator = 'like';
                        $value = '%'.$value.'%';
                    }elseif($m[3] == 'gt'){
                        $operator = '>=';
                    }elseif($m[3] == 'lt'){
                        $operator = '<=';
                    }

                    if( $related_column_name !== '' ){  // search at related table column

                        $categories = $categories->whereHas($collumn_name, function($q) use($related_column_name, $operator, $value){
    						$q->where( $related_column_name, $operator, $value );
                        });
                        
                    }else{
                        $categories = $categories->where( $collumn_name, $operator, $value );
                    }
                }
            }
        }
        $categories = $categories->get();



        // (2)sort
        $q_s = $request->input('q.s');
        if($q_s){

            // sort dir and sort column
            if( substr( $q_s,-5,5 ) === '_desc' ){
                $sort_column = substr( $q_s, 0, strlen($q_s)-5 );
                $categories = $categories->sortByDesc($sort_column);
            }elseif( substr( $q_s,-4,4 ) === '_asc' ){
                $sort_column = substr( $q_s, 0, strlen($q_s)-4 );
                $categories = $categories->sortBy($sort_column);
            }
            
        }else{
            $categories = $categories->sortByDesc('id');
        }



        // (3)paginate
        $categories = $categories->paginate(10);

		return view('categories.index', compact('categories'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create')->with( 'lists', Category::getLists() );
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->varidate($request);

        $input = $request->input('model');

        DB::beginTransaction();


		//create data
		$category = Category::create( $input );

        //sync(attach/detach)
        if($request->input('pivots')){
            $this->sync($request->input('pivots'), $category);
        }
        
        DB::commit();

		return redirect()->route('categories.index')->with('message', 'Item created successfully.');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
		return view('categories.show', compact('category'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
		return view('categories.edit', compact('category'))->with( 'lists', Category::getLists() );
    }



	/**
	 * Show the form for duplicatting the specified resource.
	 *
	 * @param \App\Category  $category	 * @return \Illuminate\Http\Response
	 */
	public function duplicate(Category $category)
	{
		return view('categories.duplicate', compact('category'))->with( 'lists', Category::getLists() );
	}



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category     * @return \Illuminate\Http\Response
     */
    public function update(Category $category, Request $request)
    {
        $this->varidate($request, $category);

        $input = $request->input('model');

        DB::beginTransaction();


		//update data
		$category->update( $input );

        //sync(attach/detach)
        if($request->input('pivots')){
            $this->sync($request->input('pivots'), $category);
        }
        
        DB::commit();
        
		return redirect()->route('categories.index')->with('message', 'Item updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
		$category->delete();
		return redirect()->route('categories.index')->with('message', 'Item deleted successfully.');
    }

    /**
     * Varidate input data.
     *
     * @return array
     */
    public function varidate(Request $request, Category $category = null)
    {
        $request->validate(Category::getValidateRule($category));
    }

    /**
     * sync pivot data
     *
     * @return void
     */
    public function sync($pivots_data, Category $category)
    {
        foreach( $pivots_data as $pivot_child_model_name => $pivots ){

            // remove 'id'
            foreach($pivots as &$value){
                if( array_key_exists('id', $value) ){
                    unset($value['id']);
                }
            }unset($value);

            $method = camel_case( str_plural($pivot_child_model_name) );
            $category->$method()->sync($pivots);
        }
    }
}

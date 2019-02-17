<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$posts = new Post;

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

                        $posts = $posts->whereHas($collumn_name, function($q) use($related_column_name, $operator, $value){
    						$q->where( $related_column_name, $operator, $value );
                        });
                        
                    }else{
                        $posts = $posts->where( $collumn_name, $operator, $value );
                    }
                }
            }
        }
        $posts = $posts->get();



        // (2)sort
        $q_s = $request->input('q.s');
        if($q_s){

            // sort dir and sort column
            if( substr( $q_s,-5,5 ) === '_desc' ){
                $sort_column = substr( $q_s, 0, strlen($q_s)-5 );
                $posts = $posts->sortByDesc($sort_column);
            }elseif( substr( $q_s,-4,4 ) === '_asc' ){
                $sort_column = substr( $q_s, 0, strlen($q_s)-4 );
                $posts = $posts->sortBy($sort_column);
            }
            
        }else{
            $posts = $posts->sortByDesc('id');
        }



        // (3)paginate
        $posts = $posts->paginate(10);

		return view('posts.index', compact('posts'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with( 'lists', Post::getLists() );
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
		$post = Post::create( $input );

        //sync(attach/detach)
        if($request->input('pivots')){
            $this->sync($request->input('pivots'), $post);
        }
        
        DB::commit();

		return redirect()->route('posts.index')->with('message', 'Item created successfully.');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
		return view('posts.show', compact('post'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
		return view('posts.edit', compact('post'))->with( 'lists', Post::getLists() );
    }



	/**
	 * Show the form for duplicatting the specified resource.
	 *
	 * @param \App\Post  $post	 * @return \Illuminate\Http\Response
	 */
	public function duplicate(Post $post)
	{
		return view('posts.duplicate', compact('post'))->with( 'lists', Post::getLists() );
	}



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, Request $request)
    {
        $this->varidate($request, $post);

        $input = $request->input('model');

        DB::beginTransaction();


		//update data
		$post->update( $input );

        //sync(attach/detach)
        if($request->input('pivots')){
            $this->sync($request->input('pivots'), $post);
        }
        
        DB::commit();
        
		return redirect()->route('posts.index')->with('message', 'Item updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
		$post->delete();
		return redirect()->route('posts.index')->with('message', 'Item deleted successfully.');
    }

    /**
     * Varidate input data.
     *
     * @return array
     */
    public function varidate(Request $request, Post $post = null)
    {
        $request->validate(Post::getValidateRule($post));
    }

    /**
     * sync pivot data
     *
     * @return void
     */
    public function sync($pivots_data, Post $post)
    {
        foreach( $pivots_data as $pivot_child_model_name => $pivots ){

            // remove 'id'
            foreach($pivots as &$value){
                if( array_key_exists('id', $value) ){
                    unset($value['id']);
                }
            }unset($value);

            $method = camel_case( str_plural($pivot_child_model_name) );
            $post->$method()->sync($pivots);
        }
    }
}

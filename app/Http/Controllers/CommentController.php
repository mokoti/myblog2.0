<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$comments = new Comment;

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

                        $comments = $comments->whereHas($collumn_name, function($q) use($related_column_name, $operator, $value){
    						$q->where( $related_column_name, $operator, $value );
                        });
                        
                    }else{
                        $comments = $comments->where( $collumn_name, $operator, $value );
                    }
                }
            }
        }
        $comments = $comments->get();



        // (2)sort
        $q_s = $request->input('q.s');
        if($q_s){

            // sort dir and sort column
            if( substr( $q_s,-5,5 ) === '_desc' ){
                $sort_column = substr( $q_s, 0, strlen($q_s)-5 );
                $comments = $comments->sortByDesc($sort_column);
            }elseif( substr( $q_s,-4,4 ) === '_asc' ){
                $sort_column = substr( $q_s, 0, strlen($q_s)-4 );
                $comments = $comments->sortBy($sort_column);
            }
            
        }else{
            $comments = $comments->sortByDesc('id');
        }



        // (3)paginate
        $comments = $comments->paginate(10);

		return view('comments.index', compact('comments'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comments.create')->with( 'lists', Comment::getLists() );
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
		$comment = Comment::create( $input );

        //sync(attach/detach)
        if($request->input('pivots')){
            $this->sync($request->input('pivots'), $comment);
        }
        
        DB::commit();

		return redirect()->route('comments.index')->with('message', 'Item created successfully.');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
		return view('comments.show', compact('comment'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
		return view('comments.edit', compact('comment'))->with( 'lists', Comment::getLists() );
    }



	/**
	 * Show the form for duplicatting the specified resource.
	 *
	 * @param \App\Comment  $comment	 * @return \Illuminate\Http\Response
	 */
	public function duplicate(Comment $comment)
	{
		return view('comments.duplicate', compact('comment'))->with( 'lists', Comment::getLists() );
	}



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment     * @return \Illuminate\Http\Response
     */
    public function update(Comment $comment, Request $request)
    {
        $this->varidate($request, $comment);

        $input = $request->input('model');

        DB::beginTransaction();


		//update data
		$comment->update( $input );

        //sync(attach/detach)
        if($request->input('pivots')){
            $this->sync($request->input('pivots'), $comment);
        }
        
        DB::commit();
        
		return redirect()->route('comments.index')->with('message', 'Item updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
		$comment->delete();
		return redirect()->route('comments.index')->with('message', 'Item deleted successfully.');
    }

    /**
     * Varidate input data.
     *
     * @return array
     */
    public function varidate(Request $request, Comment $comment = null)
    {
        $request->validate(Comment::getValidateRule($comment));
    }

    /**
     * sync pivot data
     *
     * @return void
     */
    public function sync($pivots_data, Comment $comment)
    {
        foreach( $pivots_data as $pivot_child_model_name => $pivots ){

            // remove 'id'
            foreach($pivots as &$value){
                if( array_key_exists('id', $value) ){
                    unset($value['id']);
                }
            }unset($value);

            $method = camel_case( str_plural($pivot_child_model_name) );
            $comment->$method()->sync($pivots);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$tags = new Tag;

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

                        $tags = $tags->whereHas($collumn_name, function($q) use($related_column_name, $operator, $value){
    						$q->where( $related_column_name, $operator, $value );
                        });
                        
                    }else{
                        $tags = $tags->where( $collumn_name, $operator, $value );
                    }
                }
            }
        }
        $tags = $tags->get();



        // (2)sort
        $q_s = $request->input('q.s');
        if($q_s){

            // sort dir and sort column
            if( substr( $q_s,-5,5 ) === '_desc' ){
                $sort_column = substr( $q_s, 0, strlen($q_s)-5 );
                $tags = $tags->sortByDesc($sort_column);
            }elseif( substr( $q_s,-4,4 ) === '_asc' ){
                $sort_column = substr( $q_s, 0, strlen($q_s)-4 );
                $tags = $tags->sortBy($sort_column);
            }
            
        }else{
            $tags = $tags->sortByDesc('id');
        }



        // (3)paginate
        $tags = $tags->paginate(10);

		return view('tags.index', compact('tags'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tags.create')->with( 'lists', Tag::getLists() );
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
		$tag = Tag::create( $input );

        //sync(attach/detach)
        if($request->input('pivots')){
            $this->sync($request->input('pivots'), $tag);
        }
        
        DB::commit();

		return redirect()->route('tags.index')->with('message', 'Item created successfully.');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
		return view('tags.show', compact('tag'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
		return view('tags.edit', compact('tag'))->with( 'lists', Tag::getLists() );
    }



	/**
	 * Show the form for duplicatting the specified resource.
	 *
	 * @param \App\Tag  $tag	 * @return \Illuminate\Http\Response
	 */
	public function duplicate(Tag $tag)
	{
		return view('tags.duplicate', compact('tag'))->with( 'lists', Tag::getLists() );
	}



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag     * @return \Illuminate\Http\Response
     */
    public function update(Tag $tag, Request $request)
    {
        $this->varidate($request, $tag);

        $input = $request->input('model');

        DB::beginTransaction();


		//update data
		$tag->update( $input );

        //sync(attach/detach)
        if($request->input('pivots')){
            $this->sync($request->input('pivots'), $tag);
        }
        
        DB::commit();
        
		return redirect()->route('tags.index')->with('message', 'Item updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
		$tag->delete();
		return redirect()->route('tags.index')->with('message', 'Item deleted successfully.');
    }

    /**
     * Varidate input data.
     *
     * @return array
     */
    public function varidate(Request $request, Tag $tag = null)
    {
        $request->validate(Tag::getValidateRule($tag));
    }

    /**
     * sync pivot data
     *
     * @return void
     */
    public function sync($pivots_data, Tag $tag)
    {
        foreach( $pivots_data as $pivot_child_model_name => $pivots ){

            // remove 'id'
            foreach($pivots as &$value){
                if( array_key_exists('id', $value) ){
                    unset($value['id']);
                }
            }unset($value);

            $method = camel_case( str_plural($pivot_child_model_name) );
            $tag->$method()->sync($pivots);
        }
    }
}

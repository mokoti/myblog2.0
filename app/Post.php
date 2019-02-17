<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Post extends Model
{
    use SoftDeletes;


	// Mass Assignment
	protected $fillable = ['title','body','category_id',];
    protected $dates = ['deleted_at'];


	// Validate Rule
    public static function getValidateRule(Post $post=null){
        if($post){
            $ignore_unique = $post->id;
        }else{
            $ignore_unique = 'NULL';
        }
        $table_name = 'posts';
        $validation_rule = [

            'model.title' => 'required',
            'model.body' => 'nullable',
            'model.category_id' => 'integer|nullable',

        	'pivots.tag.*.priority' => 'integer|nullable',

        ];
        if($post){

        }
        return $validation_rule;
    }

	public function comments() {
		return $this->hasMany('App\Comment');
	}


	public function category() {
		return $this->belongsTo('App\Category');
	}


	public function tags() {
		return $this->belongsToMany('App\Tag')
		->withPivot('priority')
		->orderBy('id')
		->withTimestamps();
	}


	public static function getLists() {
		$lists = [];
		$lists['Category'] = Category::pluck( 'name' ,'id' );
		$lists['Comment'] = Comment::pluck( 'title' ,'id' );
		$lists['Tag'] = Tag::pluck( 'name' ,'id' );
		return $lists;
	}
}

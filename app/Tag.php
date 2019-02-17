<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tag extends Model
{
    use SoftDeletes;


	// Mass Assignment
	protected $fillable = ['name',];
    protected $dates = ['deleted_at'];


	// Validate Rule
    public static function getValidateRule(Tag $tag=null){
        if($tag){
            $ignore_unique = $tag->id;
        }else{
            $ignore_unique = 'NULL';
        }
        $table_name = 'tags';
        $validation_rule = [

            'model.name' => 'required|unique:'.$table_name.',email,'.$ignore_unique.',id,deleted_at,NOT_NULL,deleted_at,NOT_NULL',

        	'pivots.post.*.priority' => 'integer|nullable',

        ];
        if($tag){

        }
        return $validation_rule;
    }





	public function posts() {
		return $this->belongsToMany('App\Post')
		->withPivot('priority')
		->orderBy('id')
		->withTimestamps();
	}


	public static function getLists() {
		$lists = [];
		$lists['Post'] = Post::pluck( 'title' ,'id' );
		return $lists;
	}
}

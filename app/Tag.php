<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tag extends Model
{
    // 論理削除をする
    use SoftDeletes;

	// Tagテーブルで使用する変数
	protected $fillable = ['name',];
    protected $dates = ['deleted_at'];

	// バリデーション
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
        // ??
        if($tag){
        }
        return $validation_rule;
    }

    // ポストモデルを取得
	public function posts() {
		return $this->belongsToMany('App\Post')
		->withPivot('priority')
		->orderBy('id')
		->withTimestamps();
	}

    // listとしてポストデータを取得
	public static function getLists() {
		$lists = [];
		$lists['Post'] = Post::pluck( 'title' ,'id' );
		return $lists;
	}
}

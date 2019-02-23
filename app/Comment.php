<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model
{
    // 論理削除をする
    use SoftDeletes;

	// Commentテーブルで参照する変数
	protected $fillable = ['title','body','post_id',];
    protected $dates = ['deleted_at'];

	// バリデーション
    public static function getValidateRule(Comment $comment=null){
        if($comment){
            $ignore_unique = $comment->id;
        }else{
            $ignore_unique = 'NULL';
        }
        $table_name = 'comments';
        $validation_rule = [
            'model.title' => 'required',
            'model.body' => 'nullable',
            'model.post_id' => 'integer|nullable',
        ];
        // ??
        if($comment){
        }
        return $validation_rule;
    }
    
    // ポストを取得
	public function post() {
		return $this->belongsTo('App\Post');
	}

    // listとしてポストデータを取得
	public static function getLists() {
		$lists = [];
		$lists['Post'] = Post::pluck( 'title' ,'id' );
		return $lists;
	}
}

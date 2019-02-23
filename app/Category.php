<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    // 論理削除をする
    use SoftDeletes;

	// カテゴリーテーブルで参照する変数
	protected $fillable = ['name',];
    protected $dates = ['deleted_at'];

	// バリデーション
    public static function getValidateRule(Category $category=null){
        if($category){
            $ignore_unique = $category->id;
        }else{
            $ignore_unique = 'NULL';
        }
        $table_name = 'categories';
        $validation_rule = [
            'model.name' => 'required|unique:'.$table_name.',email,'.$ignore_unique.',id,deleted_at,NOT_NULL,deleted_at,NOT_NULL',
        ];
        // ??
        if($category){
        }
        return $validation_rule;
    }

    // ポストを取得
	public function posts() {
		return $this->hasMany('App\Post');
	}

    // listとして全ポストデータを取得
	public static function getLists() {
		$lists = [];
		$lists['Post'] = Post::pluck( 'title' ,'id' );
		return $lists;
	}
}

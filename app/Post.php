<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Post extends Model
{
    // 論理削除を行う
    use SoftDeletes;

	// Postテーブルに用いるデータ
    protected $fillable = ['title','body','category_id',];
    protected $dates = ['deleted_at'];

	// バリデーション
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
        // これは何？
        if($post){
        }
        return $validation_rule;
    }

    // ブログのポストのコメントを取得
	public function comments() {
        // 1(post)対多(comments)
        // Commentモデルの外部キーは自動的にpost_idになる
		return $this->hasMany('App\Comment');
	}

    // カテゴリーを取得
	public function category() {
        // １(category)対多(posts)(Inverse)
		return $this->belongsTo('App\Category');
	}

    // タグを取得
	public function tags() {
        // 多対多
		return $this->belongsToMany('App\Tag')
        // 中間テーブル
		->withPivot('priority')
		->orderBy('id')
		->withTimestamps();
	}

    // 指定している３つのキーの値をlistで返す
	public static function getLists() {
		$lists = [];
        // 指定したキーの全コレクションを取得、
        // なのでモデルとして生成されている下記３つの全ての値をを取得する(どうなんだこれ・・・)
        // おそらくモデルであらかじめ取得する処理を前提にしているのでDBへの接続はここではないはず
		$lists['Category'] = Category::pluck( 'name' ,'id' );
		$lists['Comment'] = Comment::pluck( 'title' ,'id' );
		$lists['Tag'] = Tag::pluck( 'name' ,'id' );
		return $lists;
	}
}

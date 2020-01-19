<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	//protected $guarded   = [];
    //protected  $fillable = ['title','slug'];

    public function tags()
    {
    	return $this->belongsToMany('App\Model\Tag','post__tags');
    }
    public function categorys()
    {
    	return $this->belongsToMany('App\Model\Category','category_posts');
    }
}
